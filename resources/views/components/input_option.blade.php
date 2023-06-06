<div class="form-inline mb-3">
    <div class="input-group-prepend">
        <span class="input-group-text">{{$title}}</span>
    </div>
    @foreach ($options as $item => $text)
    <label class="radio-inline mx-2"><input name="{{$name}}" type="radio" class="mr-2" value="{{$item}}" @checked($value == $item)> {{$text}}</label>
    @endforeach
    <span class="text-muted ml-3">{{$note??''}}</span>
</div>