<form>
    <div class="form-group">
        <div class="row">
            <div class="col-12">
                <label for="tag-{{$id}}">Tag</label>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($tag)
                    <input type="text" value="{{$tag}}" class="tags form-control" id="tag-{{$id}}"/>
                @else
                    <input type="text"  class="tags form-control" id="tag-{{$id}}"/>
                @endif
            </div>
        </div>

    </div>
</form>

<script>

    $('.tags').tagsinput({
        id:"{{$asin_id}}",
        maxChars: 255,
        onTagExists: function(item, $tag) {
            console.log('tag exist');
        },
        typeaheadjs: {
            name: 'citynames',
            displayKey: 'name',
            valueKey: 'name',
            source: function(query, syncResults, asyncResults) {
                $.get('/tags?q=' + query, function(data) {
                    asyncResults(data);
                });
            }
        }
    });

    $('.tags').on('itemAdded', function(event) {
        var tag = event.item;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route("addTag",$id)}}",
            method:"POST",
            data: {name:tag}
        }).done(function (data) {
            console.log(data);
        });
    });
    $('.tags').on('beforeItemRemove', function(event) {
        event.preventDefault();
    });

    $('.tags').on('itemRemoved', function(event) {
        var tag = event.item;
        event.preventDefault();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route("removeTag",$id)}}",
            method:"DELETE",
            data: {name:tag}
        }).done(function (data) {
            console.log(data);
        });
    });
    $('.label-tag').click(function() {
        var tag = $(this).text();
        window.location = "/view/{{$asin_id}}/"+tag;
    })
</script>
<style>
    .label-tag{
        display:inline ;
    }
</style>
