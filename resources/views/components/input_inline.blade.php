<div class="form-inline mb-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">{{$title}}
                @if (isset($required))
                <strong class="text-danger ml-2" title="必填字段">*</strong>
                @endif
            </span>
        </div>
        <input name="{{$name}}" autocomplete="off" @class(['form-control', 'is-invalid'=> $errors->has($name)]) type="text" placeholder="{{$placeholder??''}}" size="{{$size??80}}" value="{{old($name,$value)}}" />
    </div>
    <span class="text-muted ml-3">{{$note??''}}</span>
</div>