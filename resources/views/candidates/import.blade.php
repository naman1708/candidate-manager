@extends('layouts.main')

@push('page-title')
    <title>{{ __('Bulk Upload Candidates') }}</title>
@endpush

@push('heading')
    {{ __('Bulk Upload Candidates') }}
@endpush

@push('style')
@endpush

@section('content')
    <x-status-message />
    <div id="successMessage" style="display: none;" class="alert alert-success mt-3">
        File import successful!
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('candidate.import') }}" method="POST" enctype="multipart/form-data"
                        class="dropzone" id="image-upload">
                        @csrf
                        <div class="fallback">
                            <input name="file" type="file" multiple="multiple">
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="dz-message needsclick">
                            <div class="mb-3">
                                <i class="display-4 text-muted ri-upload-cloud-2-line"></i>
                            </div>
                            <h4>Drop files here or click to upload.</h4>
                        </div>

                    </form>
                    <div class="text-center mt-4">
                        <button type="submit" id="uploadFile" class="btn btn-primary waves-effect waves-light">Import
                            File</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- Upload file Script --}}
    <script type="text/javascript">
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone(".dropzone", {
            autoProcessQueue: false,
            maxFilesize: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.csv,.xlsx,.xls",
            init: function() {
                this.on("success", function(file, response) {
                    $('#successMessage').show();
                });
            }
        });
        $('#uploadFile').click(function(e) {
            e.preventDefault();
            myDropzone.processQueue();
        });
    </script>
@endpush
