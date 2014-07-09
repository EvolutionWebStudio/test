<form role="form">
    <div class="form-group">
        <input class="form-control input-lg" placeholder="Message to" type="text">
    </div>
    <div class="form-group">
        <input class="form-control input-lg" placeholder="Message subject" type="text">
    </div>
    <div class="form-group">
        <textarea class="summernote-small form-control"></textarea>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <button type="submit" class="btn btn-success btn-sm">Send</button>
            <button class="btn btn-warning btn-sm">Draft</button>
            <a href="index.php?page=inbox" class="btn btn-danger btn-sm">Discard</a>
        </div>
        <div class="col-xs-4">
            <p class="quick-post message">
                <a><i class="fa fa-picture-o"></i></a>
                <a><i class="fa fa-video-camera"></i></a>
                <a><i class="fa fa-paperclip"></i></a>
            </p>
        </div>
    </div>
</form>