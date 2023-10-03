<div class="mx-3 mt-4">
    <form action="{{ $action }}" method="{{ $method }}">
        <div class="row justify-content-end">
            <div class="col-md-3 col-sm-4 col-9">
                <div class="input-group">
                    <input type="text" class="form-control {{ $class }}" id="{{ $id }}"
                        name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}">
                        <input type="hidden" name="{{$roleName}}" value="{{ $catVal }}">
                    <div class="input-group-append">
                        <button type="{{ $btn_type }}" class="btn btn-primary {{ $btnClass }}">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


