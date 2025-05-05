<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\NoticeBoard;
use App\Models\UserNoticeRead;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Mail\NoticeMail;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;

use App\Services\FileUploadService;
use App\Services\LocationService;

use Illuminate\Support\Facades\Storage;
use Exception;
use App\Models\EmployeeDepartment;



class NoticeBoardController extends Controller
{
    protected $fileUploadService;
    protected $locationService;

    public function __construct(
        FileUploadService $fileUploadService,
        LocationService $locationService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->locationService = $locationService;
    }

    
    public function index(Request $request)
    {
        $departments = EmployeeDepartment::all();
        $canManage = auth()->user()->can('manage_notice');
        $can_create = auth()->user()->can('notice.create');
        $divisions = Division::all();

        return view('notice_board.index', compact('departments', 'canManage','can_create', 'divisions'));
    }


    public function create()
    {
        $divisions = Division::all();
        return view('notice_board.create', compact('divisions'));
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,publish',
            'file_paths' => 'nullable|array',
            'file_paths.*' => 'file|mimes:jpg,png,pdf,docx|max:51200',
            'email_send' => 'required|in:yes,no',
            'pinned' => 'nullable',
            'division_ids' => 'nullable|array',
            'division_ids.*' => 'exists:divisions,id',
            'district_ids' => 'nullable|array',
            'district_ids.*' => 'exists:districts,id',
            'upazila_ids' => 'nullable|array',
            'upazila_ids.*' => 'exists:upazilas,id',
            'union_ids' => 'nullable|array',
            'union_ids.*' => 'exists:unions,id',
        ]);

        DB::beginTransaction();

        try {
            $notice_data = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'email_send' => $request->email_send,
                'pinned' => $request->pinned ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Handle file uploads
            if ($request->hasFile('file_paths')) {
                $file_paths = $this->fileUploadService->uploadFilesMultiple($request, 'file_paths', 'notice/files');
                $notice_data['file_paths'] = json_encode($file_paths);
            }

            // Insert notice
            $noticeId = DB::table('notice_boards')->insertGetId($notice_data);

            // Prepare location data
            $locationData = [];

            // Fetch all available locations
            $divisions = Division::all();
            $districts = District::all();
            $upazilas = Upazila::all();
            $unions = Union::all();

            // Determine location coverage
            $coverAllDivisions = in_array('all', $request->division_ids ?? []);
            $coverAllDistricts = in_array('all', $request->district_ids ?? []);
            $coverAllUpazilas = in_array('all', $request->upazila_ids ?? []);
            $coverAllUnions = in_array('all', $request->union_ids ?? []);

            // If no specific selections are made, default to full coverage
            if (
                empty($request->division_ids) &&
                empty($request->district_ids) &&
                empty($request->upazila_ids) &&
                empty($request->union_ids)
            ) {
                $coverAllDivisions = true;
            }

            // Add locations based on selection or default full coverage
            if ($coverAllDivisions) {
                // Cover all divisions
                foreach ($divisions as $division) {
                    $locationData[] = [
                        'notice_board_id' => $noticeId,
                        'division_id' => $division->id,
                    ];
                }
            } else {
                // Add selected divisions
                foreach ($request->division_ids ?? [] as $division_id) {
                    $locationData[] = [
                        'notice_board_id' => $noticeId,
                        'division_id' => $division_id,
                    ];
                }
            }

            // Handle districts
            if ($coverAllDivisions || $coverAllDistricts) {
                // Cover all districts if all divisions are selected or 'all districts' is chosen
                foreach ($districts as $district) {
                    $locationData[] = [
                        'notice_board_id' => $noticeId,
                        'district_id' => $district->id,
                    ];
                }
            } else {
                // Add selected districts or districts in selected divisions
                $selectedDistrictIds = $request->district_ids ?? [];

                // If no districts selected, get districts from selected divisions
                if (empty($selectedDistrictIds) && !empty($request->division_ids)) {
                    $selectedDistrictIds = District::whereIn('division_id', $request->division_ids)->pluck('id')->toArray();
                }

                foreach ($selectedDistrictIds as $district_id) {
                    $locationData[] = [
                        'notice_board_id' => $noticeId,
                        'district_id' => $district_id,
                    ];
                }
            }

            // Handle upazilas
            if ($coverAllDivisions || $coverAllDistricts || $coverAllUpazilas) {
                // Cover all upazilas if all divisions/districts are selected or 'all upazilas' is chosen
                foreach ($upazilas as $upazila) {
                    $locationData[] = [
                        'notice_board_id' => $noticeId,
                        'upazila_id' => $upazila->id,
                    ];
                }
            } else {
                // Add selected upazilas or upazilas in selected districts
                $selectedUpazilaIds = $request->upazila_ids ?? [];

                // If no upazilas selected, get upazilas from selected districts
                if (empty($selectedUpazilaIds) && !empty($request->district_ids)) {
                    $selectedUpazilaIds = Upazila::whereIn('district_id', $request->district_ids)->pluck('id')->toArray();
                }

                foreach ($selectedUpazilaIds as $upazila_id) {
                    $locationData[] = [
                        'notice_board_id' => $noticeId,
                        'upazila_id' => $upazila_id,
                    ];
                }
            }

            // Handle unions
            if ($coverAllDivisions || $coverAllDistricts || $coverAllUpazilas || $coverAllUnions) {
                // Cover all unions if all higher levels are selected or 'all unions' is chosen
                foreach ($unions as $union) {
                    $locationData[] = [
                        'notice_board_id' => $noticeId,
                        'union_id' => $union->id,
                    ];
                }
            } else {
                // Add selected unions or unions in selected upazilas
                $selectedUnionIds = $request->union_ids ?? [];

                // If no unions selected, get unions from selected upazilas
                if (empty($selectedUnionIds) && !empty($request->upazila_ids)) {
                    $selectedUnionIds = Union::whereIn('upazila_id', $request->upazila_ids)->pluck('id')->toArray();
                }

                foreach ($selectedUnionIds as $union_id) {
                    $locationData[] = [
                        'notice_board_id' => $noticeId,
                        'union_id' => $union_id,
                    ];
                }
            }

            // Insert location data with unique constraints
            $locationData = array_map('unserialize', array_unique(array_map('serialize', $locationData)));
            DB::table('notice_location')->insert($locationData);

            DB::commit();

            return redirect()->route('notices.index')->with('success', 'Notice created successfully and distributed across selected locations.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('An error occurred while creating the notice: ' . $e->getMessage())->withInput();
        }
    }

//TODO:: Notice File view from modal.
    public function show($id)
    {
        try {
            $notice = NoticeBoard::with(['locations'])
                ->findOrFail($id);

            // Ensure file paths are properly formatted
            if (!empty($notice->file_paths)) {
                if (is_string($notice->file_paths)) {
                    $filePaths = json_decode($notice->file_paths, true);
                    // Check if file exists and prepend storage path if needed
                    if (is_array($filePaths)) {
                        foreach ($filePaths as $key => $path) {
                            // Make sure the file path doesn't have spaces or is properly encoded
                            $filePaths[$key] = str_replace(' ', '%20', trim($path));
                        }
                        $notice->file_paths = $filePaths;
                    }
                }
            }

            return response()->json([
                'status' => 200,
                'notice' => $notice,
                'message' => 'Notice details retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error retrieving notice details: ' . $e->getMessage()
            ], 500);
        }
    }
    public function edit($id)
    {
        $notice = NoticeBoard::findOrFail($id);

        // Fetch notice locations with their respective names
        $noticeLocations = DB::table('notice_location')
            ->leftJoin('divisions', 'notice_location.division_id', '=', 'divisions.id')
            ->leftJoin('districts', 'notice_location.district_id', '=', 'districts.id')
            ->leftJoin('upazilas', 'notice_location.upazila_id', '=', 'upazilas.id')
            ->leftJoin('unions', 'notice_location.union_id', '=', 'unions.id')
            ->where('notice_board_id', $id)
            ->select(
                'divisions.id as division_id',
                'districts.id as district_id',
                'upazilas.id as upazila_id',
                'unions.id as union_id'
            )
            ->get();

        // Prepare location IDs
        $locations = [
            'division_ids' => $noticeLocations->pluck('division_id')->unique()->filter()->values()->all(),
            'district_ids' => $noticeLocations->pluck('district_id')->unique()->filter()->values()->all(),
            'upazila_ids' => $noticeLocations->pluck('upazila_id')->unique()->filter()->values()->all(),
            'union_ids' => $noticeLocations->pluck('union_id')->unique()->filter()->values()->all(),
        ];

        // Fetch all divisions
        $divisions = Division::all();

        // Fetch districts - include both selected districts and districts from selected divisions
        $districts = District::when(!empty($locations['division_ids']), function ($query) use ($locations) {
            return $query->whereIn('division_id', $locations['division_ids']);
        })->when(!empty($locations['district_ids']), function ($query) use ($locations) {
            return $query->orWhereIn('id', $locations['district_ids']);
        })->get();

        // Fetch upazilas - include both selected upazilas and upazilas from selected districts
        $upazilas = Upazila::when(!empty($locations['district_ids']), function ($query) use ($locations) {
            return $query->whereIn('district_id', $locations['district_ids']);
        })->when(!empty($locations['upazila_ids']), function ($query) use ($locations) {
            return $query->orWhereIn('id', $locations['upazila_ids']);
        })->get();

        // Fetch unions - include both selected unions and unions from selected upazilas
        $unions = Union::when(!empty($locations['upazila_ids']), function ($query) use ($locations) {
            return $query->whereIn('upazila_id', $locations['upazila_ids']);
        })->when(!empty($locations['union_ids']), function ($query) use ($locations) {
            return $query->orWhereIn('id', $locations['union_ids']);
        })->get();

        // Prepare file paths
        $filePaths = $notice->file_paths ? json_decode($notice->file_paths, true) : [];

        return view('notice_board.update', [
            'notice' => $notice,
            'noticeLocations' => $locations,
            'divisions' => $divisions,
            'districts' => $districts,
            'upazilas' => $upazilas,
            'unions' => $unions,
            'filePaths' => $filePaths,
        ]);
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,publish',
            'file_paths' => 'nullable|array',
            'file_paths.*' => 'file|mimes:jpg,png,pdf,docx|max:51200',
            'email_send' => 'required|in:yes,no',
            'pinned' => 'nullable',
            'division_ids' => 'nullable|array',
            'division_ids.*' => 'exists:divisions,id',
            'district_ids' => 'nullable|array',
            'district_ids.*' => 'exists:districts,id',
            'upazila_ids' => 'nullable|array',
            'upazila_ids.*' => 'exists:upazilas,id',
            'union_ids' => 'nullable|array',
            'union_ids.*' => 'exists:unions,id',
        ]);

        DB::beginTransaction();

        try {
            $notice = NoticeBoard::findOrFail($id);

            $notice_data = [
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'status' => $validatedData['status'],
                'email_send' => $validatedData['email_send'],
                'pinned' => $validatedData['pinned'] ?? 0,
                'updated_at' => now(),
            ];

            // Handle file uploads
            if ($request->hasFile('file_paths')) {
                $file_paths = $this->fileUploadService->uploadFilesMultiple($request, 'file_paths', 'notice/files');
                $notice_data['file_paths'] = json_encode($file_paths);
            }

            // Update notice
            $notice->update($notice_data);

            // Remove existing locations
            DB::table('notice_location')
                ->where('notice_board_id', $id)
                ->delete();

            // Prepare location data
            $locationData = [];

            // Fetch all available locations
            $divisions = Division::all();
            $districts = District::all();
            $upazilas = Upazila::all();
            $unions = Union::all();

            // Determine location coverage
            $coverAllDivisions = in_array('all', $request->division_ids ?? []);
            $coverAllDistricts = in_array('all', $request->district_ids ?? []);
            $coverAllUpazilas = in_array('all', $request->upazila_ids ?? []);
            $coverAllUnions = in_array('all', $request->union_ids ?? []);

            // If no specific selections are made, default to full coverage
            if (
                empty($request->division_ids) &&
                empty($request->district_ids) &&
                empty($request->upazila_ids) &&
                empty($request->union_ids)
            ) {
                $coverAllDivisions = true;
            }

            // Add locations based on selection or default full coverage
            if ($coverAllDivisions) {
                // Cover all divisions
                foreach ($divisions as $division) {
                    $locationData[] = [
                        'notice_board_id' => $id,
                        'division_id' => $division->id,
                    ];
                }
            } else {
                // Add selected divisions
                foreach ($request->division_ids ?? [] as $division_id) {
                    $locationData[] = [
                        'notice_board_id' => $id,
                        'division_id' => $division_id,
                    ];
                }
            }

            // Handle districts
            if ($coverAllDivisions || $coverAllDistricts) {
                // Cover all districts if all divisions are selected or 'all districts' is chosen
                foreach ($districts as $district) {
                    $locationData[] = [
                        'notice_board_id' => $id,
                        'district_id' => $district->id,
                    ];
                }
            } else {
                // Add selected districts or districts in selected divisions
                $selectedDistrictIds = $request->district_ids ?? [];

                // If no districts selected, get districts from selected divisions
                if (empty($selectedDistrictIds) && !empty($request->division_ids)) {
                    $selectedDistrictIds = District::whereIn('division_id', $request->division_ids)->pluck('id')->toArray();
                }

                foreach ($selectedDistrictIds as $district_id) {
                    $locationData[] = [
                        'notice_board_id' => $id,
                        'district_id' => $district_id,
                    ];
                }
            }

            // Handle upazilas
            if ($coverAllDivisions || $coverAllDistricts || $coverAllUpazilas) {
                // Cover all upazilas if all divisions/districts are selected or 'all upazilas' is chosen
                foreach ($upazilas as $upazila) {
                    $locationData[] = [
                        'notice_board_id' => $id,
                        'upazila_id' => $upazila->id,
                    ];
                }
            } else {
                // Add selected upazilas or upazilas in selected districts
                $selectedUpazilaIds = $request->upazila_ids ?? [];

                // If no upazilas selected, get upazilas from selected districts
                if (empty($selectedUpazilaIds) && !empty($request->district_ids)) {
                    $selectedUpazilaIds = Upazila::whereIn('district_id', $request->district_ids)->pluck('id')->toArray();
                }

                foreach ($selectedUpazilaIds as $upazila_id) {
                    $locationData[] = [
                        'notice_board_id' => $id,
                        'upazila_id' => $upazila_id,
                    ];
                }
            }

            // Handle unions
            if ($coverAllDivisions || $coverAllDistricts || $coverAllUpazilas || $coverAllUnions) {
                // Cover all unions if all higher levels are selected or 'all unions' is chosen
                foreach ($unions as $union) {
                    $locationData[] = [
                        'notice_board_id' => $id,
                        'union_id' => $union->id,
                    ];
                }
            } else {
                // Add selected unions or unions in selected upazilas
                $selectedUnionIds = $request->union_ids ?? [];

                // If no unions selected, get unions from selected upazilas
                if (empty($selectedUnionIds) && !empty($request->upazila_ids)) {
                    $selectedUnionIds = Union::whereIn('upazila_id', $request->upazila_ids)->pluck('id')->toArray();
                }

                foreach ($selectedUnionIds as $union_id) {
                    $locationData[] = [
                        'notice_board_id' => $id,
                        'union_id' => $union_id,
                    ];
                }
            }

            // Insert location data with unique constraints
            $locationData = array_map('unserialize', array_unique(array_map('serialize', $locationData)));
            DB::table('notice_location')->insert($locationData);

            DB::commit();

            return redirect()->route('notices.index')->with('success', 'Notice updated successfully and distributed across selected locations.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('An error occurred while updating the notice: ' . $e->getMessage());
        }
    }

    public function destroy($notice_id)
    {
        $notice = NoticeBoard::find($notice_id);

        if ($notice) {
            $notice->delete();
            return response()->json(['status' => 200, 'message' => 'Notice deleted successfully']);
        }
        return response()->json(['status' => 404, 'message' => 'Notice not found']);
    }


    public function getNotices(Request $request)
    {
        $canManage = auth()->user()->can('manage_notice');
        $can_edit = auth()->user()->can('notice.edit');
        $can_delete = auth()->user()->can('notice.delete');


        $user = auth()->user();

        if ($canManage) {
            $notices = NoticeBoard::query()
                ->orderBy('pinned', 'desc')
                ->get();
        } else {
            $notices = NoticeBoard::whereHas('locations', function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('division_id', $user->division_id)
                      ->orWhere('district_id', $user->district_id)
                      ->orWhere('upazila_id', $user->upazila_id)
                      ->orWhere('union_id', $user->union_id);
                });
            })
            ->with('locations.division', 'locations.district', 'locations.upazila', 'locations.union')
            ->orderBy('pinned', 'desc')
            ->get();
        }
        
        return DataTables::of($notices)
            ->addColumn('title', function ($notice) {
                return $notice->title;
            })


            ->addColumn('created_at', function ($notice) {
                $created_at = $notice->created_at;
                return date('d-m-Y', strtotime($created_at));
            })

            ->addColumn('status', function ($notice) {

                if ($notice->status == 'draft') {
                    return '<span class="badge badge-warning">Draft</span>';
                } else {
                    return '<span class="badge badge-success">Published</span>';
                }
            })
            ->addColumn('pinned', function ($notice) {
                if ($notice->pinned == 1) {
                    return '<span class="badge badge-primary">Pinned</span>';
                }
            })
            ->addColumn('action', function ($notice) use ($can_delete, $can_edit) {
                $btn = '<a href="javascript:void(0);" class="btn btn-sm btn-alt-info mr-1" 
                        onclick=" viewNoticeDetails(' . $notice->id . ')" 
                        title="View"><i class="fa fa-fw fa-eye"></i> </a>';

                if ($can_edit) {
                    $btn .= '<a href="' . route('notices.edit', $notice->id) . '" class="btn btn-sm btn-alt-info" title="Edit">
                                <i class="fa fa-fw fa-pen text-info"></i>
                            </a>';
            } 
                if ($can_delete) {
                        $btn .= ' <a href="javascript:void(0);" class="btn btn-sm btn-alt-danger"  onclick="DeleteNotice(' . $notice->id . ')" title="Delete">
                                <i class="fa fa-fw fa-trash text-danger"></i></a>';
                    }
                return $btn;
            })
            ->rawColumns(['title', 'action', 'status', 'assigned_department', 'pinned', 'location'])
            ->make(true);
    }
}

    



