<div>
    <!-- Responsive Block Modal -->
    <div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="modal-block-normal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-{{ $size ?? 'md' }} w-100 mx-auto" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-info-dark">
                        <h3 class="block-title">{{ $title }}</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
    <!-- END Responsive Block Modal -->
</div>
