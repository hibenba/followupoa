<li class="nav-item">
    <a @class(['nav-link','active'=> $active]) href="{{$link}}">
        <i class="nav-icon {{$fa}}"></i>
        <p>{{$name}}
            @if (!empty($tip)&&$tip>0)
            <span class="badge badge-info right">{{$tip>99?'99+':$tip}}</span>
            @endif
        </p>
    </a>
</li>