@extends('layouts.header')

@section('main-content')
    <div class="container-fluid spark-screen" style="margin-top: 50px">
        <div class="row">
            <div class="col-md-12">
                <section class="content-header">
                    <h5>
                        @if($asin->url!=null)
                            Summary Tag for ASIN code <a href="https://www.amazon.com{{$asin->url}}" target="_blank">{{ $asin->code }}</a>
                        @else
                            Summary Tag for ASIN code <a href="https://www.amazon.com/product-reviews/{{ $asin->code }}" target="_blank">{{ $asin->code }}</a>
                        @endif
                    </h5>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="asin-comment" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($data)
                                        @foreach($data as $tag)
                                            <tr>
                                                <td>{{$tag->name}}</td>
                                                <td>{{$tag->total}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </section>
            </div>
        </div>
    </div>

@endsection
@section('additional-script')

@endsection
