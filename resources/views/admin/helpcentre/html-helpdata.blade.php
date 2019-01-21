<div class="col-md-4">
    <div class="portlet light bordered">
        <div class="portlet-body help_result">
            <div class="row">
                <div class="col-md-1">
                    @if($catRowfiledataFile->media_type == 1)
                    <i class="{{ $mediaTypeIcon[1] }}" style="color:red"></i>
                    @elseif($catRowfiledataFile->media_type == 2)
                    <i class="{{ $mediaTypeIcon[2] }}" style="color: blueviolet;"></i>
                    @elseif($catRowfiledataFile->media_type == 3)
                    @if(getFileExtensionFromFilename($catRowfiledataFile->file_name) == 'txt')
                    <i class="{{ $mediaTypeIcon[6] }}" ></i>
                    @elseif(getFileExtensionFromFilename($catRowfiledataFile->file_name) == 'pdf')
                    <i class="{{ $mediaTypeIcon[7] }}" style="color: red;"></i>
                    @elseif(getFileExtensionFromFilename($catRowfiledataFile->file_name) == 'ppt')
                    <i class="{{ $mediaTypeIcon[8] }}" style="color: red;"></i>
                    @elseif(getFileExtensionFromFilename($catRowfiledataFile->file_name) == 'doc' || getFileExtensionFromFilename($catRowfiledataFile->file_name) == 'docx')
                    <i class="{{ $mediaTypeIcon[9] }}" style="color: blue;"></i>
                    @else
                    <i class="{{ $mediaTypeIcon[3] }}" style="color: red;"></i>
                    @endif
                    @elseif($catRowfiledataFile->media_type == 4)
                    <i class="{{ $mediaTypeIcon[4] }}" style="color: #3598dc;"></i>
                    @elseif($catRowfiledataFile->media_type == 5)
                    <i class="{{ $mediaTypeIcon[5] }}" style="color: #26a69a;"></i>
                    @endif
                </div>
                <div class="col-md-9">
                    <p>
                        {{ $catRowfiledataFile->title }}
                    </p>
                    <p>
                        {{ 'Date Added : '.outputDateFormat($catRowfiledataFile->created_at) }}
                    </p>
                </div>
                <div class="col-md-1 pull-left">
                    @if($catRowfiledataFile->media_type == 1)
                    <a title="View" class="view_row" data-remote="{{ route('helpcentre.helpcentreview',$catRowfiledataFile->file_id) }}"><i class="glyphicon glyphicon-eye-open co" style=""></i></a>
                    @elseif($catRowfiledataFile->media_type == 2)
                    <a title="View" class="view_row" data-remote="{{ route('helpcentre.helpcentreview',$catRowfiledataFile->file_id) }}"><i class="glyphicon glyphicon-eye-open co" style=""></i></a>
                    @elseif($catRowfiledataFile->media_type == 3)
                    <a title="Download" href="{{ route('helpcentre.helpcentreview',$catRowfiledataFile->file_id) }}"><i class="fa fa-download" style=""></i></a>
                    @elseif($catRowfiledataFile->media_type == 4)
                    <a title="Download" href="{{ route('helpcentre.helpcentreview',$catRowfiledataFile->file_id) }}"><i class="fa fa-download" style=""></i></a>
                    @elseif($catRowfiledataFile->media_type == 5)
                    <a title="View" target="_blank" href="{{ route('helpcentre.helpcentreview',$catRowfiledataFile->file_id) }}"><i class="fa fa-download" style=""></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>