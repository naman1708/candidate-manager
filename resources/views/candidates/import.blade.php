@extends('layouts.main')

@push('page-title')
    <title>{{ __('Bulk Upload Candidates') }} </title>
@endpush

@push('heading')
    {{ __('Sample CSV') }}
    <a href="{{route('candidate.downloadSampleCsv')}}" class="btn btn-link">John Doe</a>

@endpush

@push('style')
@endpush

@section('content')
    <x-status-message />
    <div id="successMessage" style="display: none;" class="alert alert-success mt-3">
        File import successful!
    </div>
    <div id="errorMessage" style="display: none;" class="alert alert-danger mt-3">
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('candidate.import') }}" method="POST" enctype="multipart/form-data"
                        class="dropzone" id="file-upload">
                        @csrf
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
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#file-upload", {
            url: "{{ route('candidate.import') }}",
            autoProcessQueue: false,
            acceptedFiles: ".csv,.xlsx,.xls",
            maxFilesize: 10,
            parallelUploads: 20,
            init: function() {
                this.on("addedfile", function(file) {
                    addCancelButton(file);
                });
            },
        });

        function addCancelButton(file) {
            var cancelButton = Dropzone.createElement(
                "<button class='btn btn-danger btn-sm cancel-button text-center' style='margin-left: 30px;'>Remove</button>"
            );

            file.previewElement.appendChild(cancelButton);

            cancelButton.addEventListener("click", function() {
                myDropzone.removeFile(file);
            });
        }

        function showErrorMessage(message) {
            $("#errorMessage").show();
            $("#errorMessage").html(message);
        }

        $("#uploadFile").on("click", function() {
            var files = myDropzone.getQueuedFiles();

            if (files.length === 0) {
                showErrorMessage("Please choose a file.");
            } else {
                myDropzone.options.autoProcessQueue = true;
                myDropzone.processQueue();
            }
        });

        myDropzone.on("queuecomplete", function() {
            myDropzone.options.autoProcessQueue = false;
        });

        myDropzone.on("success", function(file, response) {
            $("#successMessage").show();
            $("#successMessage").html(response.message);
            setTimeout(() => {
                myDropzone.removeFile(file);
            }, 2000);
            console.log(response.message);
        });

        myDropzone.on("error", function(file, errorMessage) {
            console.log(errorMessage);
            showErrorMessage(errorMessage);
        });
    </script>

@endpush
