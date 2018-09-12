@extends('layouts.header')

@section('main-content')
    <div class="container-fluid spark-screen" style="margin-top: 50px">
        <div class="row">
            <div class="col-md-12">
                <section class="content-header">
                    <h5>
                        @if($asin->url!=null)
                            Comment Data for ASIN Code <a href="https://www.amazon.com{{$asin->url}}" target="_blank">{{ $asin->code }}</a>
                        @else
                            Comment Data for ASIN Code <a href="https://www.amazon.com/product-reviews/{{ $asin->code }}" target="_blank">{{ $asin->code }}</a>
                        @endif
                        @if($tag!='')
                            TAG : {{$tag}}
                        @endif
                    </h5>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row" style="margin-top: 20px">
                        <div class="col-6">
                            <div>
                                <h3><a href="{{route('viewAnalysis',$asin->id)}}">See analysis page</a></h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="float-right">
                                <div class="title-total">Total Review </div>
                                <div class="total-review">
                                    {{$total_verified+$total_unverified}}
                                </div>
                                <div class="total-verified">
                                    {{$total_verified}} +
                                </div>
                                <div class="total-unverified">
                                    {{$total_unverified}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-10">
                            <div class="float-right">
                                <div class="star">
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="float-right">
                                <div class="total">
                                    {{$total_verified_five_star+$total_unverified_five_star}}
                                </div>
                                <div class="verified">
                                    {{$total_verified_five_star}}
                                </div>
                                +
                                <div class="unverified">
                                    {{$total_unverified_five_star}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-10">
                            <div class="float-right">
                                <div class="star">
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark">star_border</i>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="float-right">
                                <div class="total">
                                    {{$total_verified_four_star+$total_unverified_four_star}}
                                </div>
                                <div class="verified">
                                    {{$total_verified_four_star}}
                                </div>
                                +
                                <div class="unverified">
                                    {{$total_unverified_four_star}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-10">
                            <div class="float-right">
                                <div class="star">
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark">star_border</i>
                                    <i class="material-icons md-dark">star_border</i>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="float-right">
                                <div class="total">
                                    {{$total_verified_three_star+$total_unverified_three_star}}
                                </div>
                                <div class="verified">
                                    {{$total_verified_three_star}}
                                </div>
                                +
                                <div class="unverified">
                                    {{$total_unverified_three_star}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-10">
                            <div class="float-right">
                                <div class="star">
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark">star_border</i>
                                    <i class="material-icons md-dark">star_border</i>
                                    <i class="material-icons md-dark">star_border</i>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="float-right">
                                <div class="total">
                                    {{$total_verified_two_star+$total_unverified_two_star}}
                                </div>
                                <div class="verified">
                                    {{$total_verified_two_star}}
                                </div>
                                +
                                <div class="unverified">
                                    {{$total_unverified_two_star}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-10">
                            <div class="float-right">
                                <div class="star">
                                    <i class="material-icons md-dark" style="color: yellow" >star</i>
                                    <i class="material-icons md-dark">star_border</i>
                                    <i class="material-icons md-dark">star_border</i>
                                    <i class="material-icons md-dark">star_border</i>
                                    <i class="material-icons md-dark">star_border</i>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="float-right">
                                <div class="total">
                                    {{$total_verified_one_star+$total_unverified_one_star}}
                                </div>
                                <div class="verified">
                                    {{$total_verified_one_star}}
                                </div>
                                +
                                <div class="unverified">
                                    {{$total_unverified_one_star}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12"  style="margin-top: 20px">
                            @if($asin->is_finished)
                                {{--<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Success!</strong> All data has been scraped
                                </div>--}}
                            @else
                                <div class="alert alert-warning">
                                    <strong>Warning!</strong> Scrapping data still progress. Please book mark this page and come back later
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form class="form-inline">
                                <div class="form-check mb-3 mr-sm-3">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified"> Verified review
                                    </label>
                                </div>
                                <div class="form-check mb-3 mr-sm-3">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="star_one" id="star_one"> 1 star
                                    </label>
                                </div>
                                <div class="form-check mb-3 mr-sm-3">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="star_two" id="star_two"> 2 star
                                    </label>
                                </div>
                                <div class="form-check mb-3 mr-sm-3">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="star_three" id="star_three"> 3 star
                                    </label>
                                </div>
                                <div class="form-check mb-3 mr-sm-3">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="star_four" id="star_four"> 4 star
                                    </label>
                                </div>
                                <div class="form-check mb-3 mr-sm-3">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="star_five" id="star_five"> 5 star
                                    </label>
                                </div>
                                <div class="form-check mb-3 mr-sm-3">
                                    <label class="form-check-label">
                                        Child Variation
                                    </label>
                                </div>
                                <select class="form-control mb-3 mr-sm-3" id="asin_child" name="asin_child">
                                    <option value="0">All</option>
                                    @foreach($child_asin as $child)
                                        <option value="{{$child->asin_child}}">{{$child->asin_child}}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-primary mb-2" id="filter">Filter</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="asin-comment" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Tag</th>
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
            function format ( d ) {
                return d.input_tag;
            }
            var oTable =  $('#asin-comment').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('viewAsinCodeData',[$id,$tag]) }}",
                    data: function (d) {
                        d.asin_child = $('#asin_child').find(":selected").val();
                        d.is_verified = $("#is_verified").is(":checked");
                        d.star_one = $("#star_one").is(":checked");
                        d.star_two = $("#star_two").is(":checked");
                        d.star_three = $("#star_three").is(":checked");
                        d.star_four = $("#star_four").is(":checked");
                        d.star_five = $("#star_five").is(":checked");
                    }
                },
                columns: [
                    {
                        "class":          "details-control",
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": " <i class=\"material-icons md-dark\" style='cursor:pointer'>add</i>"
                    },
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

            $('#filter').on('click', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            var detailRows = [];

            $('#asin-comment tbody').on( 'click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = oTable.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );

                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    row.child.hide();

                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );
                    row.child( format( row.data() ) ).show();

                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                }
            } );

            // On each draw, loop over the `detailRows` array and show any child rows
            oTable.on( 'draw', function () {
                $.each( detailRows, function ( i, id ) {
                    $('#'+id+' td.details-control').trigger( 'click' );
                } );
            } );
        });
    </script>
    <style>
        .star{
            display: inline;
            margin-right: 30px;
        }
        .total{
            color: #000;
            margin-left: 10px;
            font-size: 18px;
            display: inline;
        }
        .verified{
            color: #58ff25;
            margin-left: 10px;
            font-size: 18px;
            display: inline;
        }
        .unverified{
            color: #ff002a;
            font-size: 18px;
            display: inline;
        }

        .title-total{
            margin-right: 15px;
            display: inline;
            font-size: 24px;
        }

        .total-review{
            margin-left: 10px;
            display: inline;
            font-size: 24px;
        }
        .total-verified{
            margin-left: 15px;
            display: inline;
            font-size: 24px;
            color: #58ff25;
        }
        .total-unverified{
            display: inline;
            font-size: 24px;
            color: #ff002a;
        }
    </style>
@endsection
