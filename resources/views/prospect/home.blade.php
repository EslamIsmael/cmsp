@extends('layouts.prospect')

@section('content')
@include('partials.prospect_menu')
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h1>Welcome {{ auth()->user()->name }}</h1>
                </div>

                <div class="card-body">
                    @if($prospect)
                        <div class="container-fluid">
                            <div class="row flex-wrap">
                                @foreach ($prospect->files as $media)
                                    <a target="_blank" href="{{ $media->getUrl() }}" class="col-md-3">
                                        <div class="card mx-3">
                                            <div class="card-header">
                                                <i class="fas fa-file-download fa-9x"></i>
                                            </div>
                                            <div class="card-footer">
                                                {{ $media->file_name }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection