<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="{{$fa}}"></i> {{$title}}
                    @if(!empty($newitem))
                    <small><a class="ml-2 badge badge-info text-white" href="{{$link}}" {{$target?'target="_blank"':''}}>{{$newitem}}</a></small> 
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <p class="float-sm-right mb-0 mt-1 text-gray">{{$note}}</p>
            </div>
        </div>
    </div>
</div>