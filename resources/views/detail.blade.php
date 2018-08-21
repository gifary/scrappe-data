@extends('layouts.header')

@section('main-content')
    <div class="container-fluid spark-screen" style="margin-top: 50px">
        <div class="row">
            <div class="col-md-12">
                <section class="content-header">
                    <h5>
                        Comment Data for ASIN Code {{ $asin->code }}
                    </h5>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-12"  style="margin-top: 20px">
                            @if($asin->is_finished)
                                <div class="alert alert-success">
                                    <strong>Success!</strong> All data has been scraped
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <strong>Warning!</strong> Scrapping data still progress. Please book mark this page and come back later
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="asin-comment" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Body</th>
                                        <th>Score</th>
                                        <th>Date of Review</th>
                                        <th>Author</th>
                                        <th>Verified</th>
                                        <th>Is Image</th>
                                        <th>Is Video</th>
                                    </tr>
                                    </thead>
                                    <tbody>
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
    <script>
        $(function () {
            $('#asin-comment').DataTable({
                serverSide: true,
                processing: true,
                ajax: "{{ route('viewAsinCodeData',['id'=>$id]) }}",
                columns: [
                    {data: 'title'},
                    {data: 'body'},
                    {data: 'review_score'},
                    {data: 'date_of_review'},
                    {data: 'author'},
                    {data: 'is_verified'},
                    {data: 'is_image'},
                    {data: 'video_url'}
                ]
            });
        });
    </script>
@endsection
