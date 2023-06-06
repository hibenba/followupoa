<div class="form-inline mb-3">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">{{$title}}</span>
        </div>                    
        <select name="{{$name}}" class="custom-select">
            @foreach ($options as $item => $text)
            <option value="{{$item}}" @selected($value == $item)>{{$text}}</option>
            @endforeach
        </select>
    </div>
    <span class="text-muted ml-3">{{$note??''}}</span>
</div>