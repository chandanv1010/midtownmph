

$(document).ready(function(){
    $(document).ready(function(){
        $(document).on('click','.choose-image', function(){
            $(this).closest('.ibox').find('.img-thumbnail').trigger('click');
        });
    });
    
	$(document).on('click','.img-thumbnail', function(){
		BrowseServerPreview($(this));
	});
    $(document).on('click','.img_version_select', function(){
        let id = $(this).attr('data-target')
        BrowseServerProduct($(this), id);
    });
    $(document).on('click','.va-img-click', function(){
        BrowseServerInput($(this));
    });
	$(document).on('click','.uploadMultiImage', function(){
        let target = $(this).attr('data-target');
		BrowseServerEditor('Images', target);
        return false;
	});
    $(document).on('click','.uploadImage', function(){
        BrowseServerImage($(this));
        return false;
    });
     $(document).on('click','.uploadIcon', function(){
        BrowseServerIcon($(this));
        return false;
    });
    $(document).on('click','.tv-nav-tabs>li>a ', function(){
        let _this = $(this);
        let parent = _this.closest('li.tv-block');
        let target = _this.attr('href');
        parent.find('.tab-pane').removeClass('active');
        parent.find(target).addClass('active');

        return false;
  });

});

function BrowseServerImage  (object, type){
    if(typeof(type) == 'undefined'){
        type = 'Images';
    }
    var finder = new CKFinder();
    finder.resourceType = type;
    finder.selectActionFunction = function( fileUrl, data ) {
        fileUrl =  fileUrl.replace(BASE_URL, "/");
        path = object.parent().siblings('.ibox-content');
        path.find('input').val(fileUrl);

        fileUrl = process_img_link_mp4(fileUrl);
        path.find('img').attr('src', fileUrl);
    }
    finder.popup();
}




function BrowseServerEditor(type, field){
    var finder = new CKFinder();
    var object  = editors[field] // Xac dinh editor ma minh muon cho anh vao
    finder.resourceType = type;
    finder.selectActionData = field;

    finder.selectActionFunction = function(fileUrl , data, allFiles ) {
        var files = allFiles;
        var content = '';
        for(var i = 0 ; i < files.length; i++){
            fileUrl =  files[i].url.replace(BASE_URL, "/");
            // content = content + '<img src="'+fileUrl+'" alt="'+fileUrl+'">';
            CKEDITOR.instances[field].insertHtml('<p><img src="'+fileUrl+'" alt="'+fileUrl+'"></p>');
        }
        // const viewFragment = object.data.processor.toView( content );
        // const modelFragment = object.data.toModel( viewFragment );
        // object.model.insertContent( modelFragment);
    }
    finder.popup();
}

function BrowseServerInput  (object, type){
    // if(typeof(type) == 'undefined'){
    //     type = 'Images';
    // }
    var finder = new CKFinder();
    finder.resourceType = type;

    finder.selectActionFunction = function( fileUrl, data ) {
        console.log(fileUrl)
        fileUrl =  fileUrl.replace(BASE_URL, "/");

        object.val(fileUrl)
    }
    finder.popup();
}

function BrowseServer  (object, type){
    if(typeof(type) == 'undefined'){
        type = 'Images';
    }
    var finder = new CKFinder();
    finder.resourceType = type;
    finder.selectActionFunction = function( fileUrl, data ) {
        console.log(fileUrl)
        fileUrl =  fileUrl.replace(BASE_URL, "/");
        $(object).val(fileUrl)
    }
    finder.popup();
}
function BrowseServerIcon  (object, type){
    if(typeof(type) == 'undefined'){
        type = 'Images';
    }
    var finder = new CKFinder();
    finder.resourceType = type;
    finder.selectActionFunction = function( fileUrl, data ) {
        console.log(fileUrl)
        fileUrl =  fileUrl.replace(BASE_URL, "/");
        $('.icon-display').val(fileUrl)
    }
    finder.popup();
}

function BrowseServerAlbum(object,name,count, type){
    var finder = new CKFinder();
    finder.resourceType = type;

    finder.selectActionFunction = function(fileUrl , data, allFiles ) {
        if(typeof(type) == 'undefined'){
            type = 'Images';
        }

        if(typeof(name) == 'undefined'){
            name = 'album';
        }else{
            name = name+'['+count+']';
        }


        var files = allFiles;
        var li = '';
        for(var i = 0 ; i < files.length; i++){
            fileUrl =  files[i].url.replace(BASE_URL, "/");
            li = li + '<li class="ui-state-default">';
                li = li + '<div class="thumb">';
                    li = li + '<span class="image img-scaledown">';
                        li = li + '<img src="'+fileUrl+'" alt="">';
                        li = li + '<input type="hidden" value="'+fileUrl+'" name="'+name+'[]">';
                    li = li + '</span>';
                    li = li + '<div class="overlay"></div><div class="delete-image"><i class="fa fa-trash" aria-hidden="true"></i></div>';
                li = li + '</div>';
            li = li + '</li>';
        }
        object.closest('.ibox-content').find('.click-to-upload').hide();
        object.closest('.ibox-content').find('.upload-list').show();
        object.parents('.album').find('.data-album').append(li);

    }
    finder.popup();
}

let albumCounter = 0;

function BrowseServerExtractAlbum(object, name, albumCounter, type) {

    var finder = new CKFinder();

    finder.resourceType = type;

    finder.selectActionFunction = function(fileUrl, data, allFiles) {
        if (typeof(type) == 'undefined') {
            type = 'Images';
        }

        name = 'extract_album[' + albumCounter + ']';

        var files = allFiles;
        var li = '';
        for (var i = 0; i < files.length; i++) {
            fileUrl = files[i].url.replace(BASE_URL, "/");
            li += '<li class="ui-state-default">';
                li += '<div class="thumb">';
                    li += '<span class="image img-scaledown">';
                        li += '<img src="' + fileUrl + '" alt="">';
                        li += '<input type="hidden" value="' + fileUrl + '" name="' + name + '[list][image][]">';
                        li += '<input class="name" type="text" value="" name="' + name + '[list][name][]">';
                    li += '</span>';
                    li += '<div class="overlay"></div><div class="delete-image"><i class="fa fa-trash" aria-hidden="true"></i></div>';
                li += '</div>';
            li += '</li>';
        }
        object.closest('.ibox-content').find('.extract_album .click-to-upload').hide();
        object.closest('.ibox-content').find('.extract_album .upload-list').show();
        object.parents('.album').find('.extract_album .data-album').append(li);
    }
    finder.popup();
}

// function BrowseServerAddAlbum(element, type, albumCounter, imageType) {
//     let html = `
//         <div class="ibox mb20 album">
//         <div class="ibox-title">
//             <div class="uk-flex uk-flex-middle uk-flex-space-between">
//                 <h5>ALBUM ẢNH </h5>
//                 <div class="uk-flex uk-flex-middle uk-flex-space-between">
//                     <div class="edit mr15">
//                         <a onclick="BrowseServerExtractAlbum($(this), 'extract_album', ${albumCounter + 1} , 'Images');return false;" href="" title="" class="upload-picture">Upload hình ảnh</a>
//                     </div>
//                     <div class="add">
//                         <a onclick="BrowseServerAddAlbum($(this), 'extract_album', ${albumCounter + 1}, 'Images');return false;" href="" title="" class="add-album">Thêm album</a>
//                     </div>
//                 </div>
//             </div>
//         </div>
//         <div class="ibox-content">
//             <div class="row extract_album">
//                 <div class="col-lg-12">
//                     <input type="text" class="mb10 form-control" name="extract_album[][name]" placeholder="Nhập tên album">
//                     <div class="click-to-upload">
//                         <div class="icon">
//                             <a type="button" class="upload-picture" onclick="BrowseServerExtractAlbum($(this), 'extract_album', ${albumCounter + 1} , 'Images');return false;">
//                                 <svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
//                             </a>
//                         </div>
//                         <div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
//                     </div>
//                     <div class="upload-list" style="display:none; margin-bottom:20px;">
//                         <div class="row">
//                             <ul id="sortable" class="clearfix data-album sortui ui-sortable">
//                             </ul>
//                         </div>
//                     </div>
//                     <textarea type="text" class="mt20 form-control" name="extract_album[${albumCounter + 1}][description]" placeholder="Nhập mô tả...."></textarea>
//                 </div>
//             </div>
//         </div>
//     </div>
//     `;
    
//     $('.album').after(html);
    
//     albumCounter++;
// }

function BrowseServerAddAlbum(element, type, albumCounter, imageType) {
    // Tính số lượng album hiện tại để tránh trùng lặp
    let currentAlbumCount = $('.album').length;
    let newAlbumIndex = currentAlbumCount;
    
    let html = `
        <div class="ibox mb20 album">
        <div class="ibox-title">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <h5>ALBUM ẢNH </h5>
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <div class="edit mr15">
                        <a onclick="BrowseServerExtractAlbum($(this), 'extract_album', ${newAlbumIndex} , 'Images');return false;" href="" title="" class="upload-picture">Upload hình ảnh</a>
                    </div>
                    <div class="add">
                        <a onclick="BrowseServerAddAlbum($(this), 'extract_album', ${newAlbumIndex}, 'Images');return false;" href="" title="" class="add-album">Thêm album</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row extract_album">
                <div class="col-lg-12">
                    <input type="text" class="mb10 form-control" name="extract_album[${newAlbumIndex}][name]" placeholder="Nhập tên album">
                    <div class="click-to-upload">
                        <div class="icon">
                            <a type="button" class="upload-picture" onclick="BrowseServerExtractAlbum($(this), 'extract_album', ${newAlbumIndex} , 'Images');return false;">
                                <svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
                            </a>
                        </div>
                        <div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
                    </div>
                    <div class="upload-list" style="display:none; margin-bottom:20px;">
                        <div class="row">
                            <ul id="sortable-${newAlbumIndex}" class="clearfix data-album sortui ui-sortable">
                            </ul>
                        </div>
                    </div>
                    <textarea type="text" class="mt20 form-control" name="extract_album[${newAlbumIndex}][description]" placeholder="Nhập mô tả...."></textarea>
                </div>
            </div>
        </div>
    </div>
    `;
    
    // Thêm album mới sau album cuối cùng
    $('.album').last().after(html);
    $( ".ui-sortable" ).sortable();
    $( ".ui-sortable" ).disableSelection();
}

function initializeAlbumSystem() {
    $(document).off('click.albumSystem').on('click.albumSystem', '.add-album', function(e) {
        e.preventDefault();
        e.stopPropagation(); 
        let currentAlbumCount = $('.album').length;
        let newAlbumIndex = currentAlbumCount;
        
        let html = `
            <div class="ibox mb20 album">
            <div class="ibox-title">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <h5>ALBUM ẢNH </h5>
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="edit mr15">
                            <a href="#" class="upload-picture" data-album="${newAlbumIndex}">Upload hình ảnh</a>
                        </div>
                        <div class="add">
                            <a href="#" class="add-album">Thêm album</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row extract_album">
                    <div class="col-lg-12">
                        <input type="text" class="mb10 form-control" name="extract_album[${newAlbumIndex}][name]" placeholder="Nhập tên album">
                        <div class="click-to-upload">
                            <div class="icon">
                                <a href="#" class="upload-picture" data-album="${newAlbumIndex}">
                                    <svg style="width:80px;height:80px;fill: #d3dbe2;margin-bottom: 10px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path d="M80 57.6l-4-18.7v-23.9c0-1.1-.9-2-2-2h-3.5l-1.1-5.4c-.3-1.1-1.4-1.8-2.4-1.6l-32.6 7h-27.4c-1.1 0-2 .9-2 2v4.3l-3.4.7c-1.1.2-1.8 1.3-1.5 2.4l5 23.4v20.2c0 1.1.9 2 2 2h2.7l.9 4.4c.2.9 1 1.6 2 1.6h.4l27.9-6h33c1.1 0 2-.9 2-2v-5.5l2.4-.5c1.1-.2 1.8-1.3 1.6-2.4zm-75-21.5l-3-14.1 3-.6v14.7zm62.4-28.1l1.1 5h-24.5l23.4-5zm-54.8 64l-.8-4h19.6l-18.8 4zm37.7-6h-43.3v-51h67v51h-23.7zm25.7-7.5v-9.9l2 9.4-2 .5zm-52-21.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-13-10v43h59v-43h-59zm57 2v24.1l-12.8-12.8c-3-3-7.9-3-11 0l-13.3 13.2-.1-.1c-1.1-1.1-2.5-1.7-4.1-1.7-1.5 0-3 .6-4.1 1.7l-9.6 9.8v-34.2h55zm-55 39v-2l11.1-11.2c1.4-1.4 3.9-1.4 5.3 0l9.7 9.7c-5.2 1.3-9 2.4-9.4 2.5l-3.7 1h-13zm55 0h-34.2c7.1-2 23.2-5.9 33-5.9l1.2-.1v6zm-1.3-7.9c-7.2 0-17.4 2-25.3 3.9l-9.1-9.1 13.3-13.3c2.2-2.2 5.9-2.2 8.1 0l14.3 14.3v4.1l-1.3.1z"></path></svg>
                                </a>
                            </div>
                            <div class="small-text">Sử dụng nút <b>Chọn hình</b> để thêm hình.</div>
                        </div>
                        <div class="upload-list" style="display:none; margin-bottom:20px;">
                            <div class="row">
                                <ul id="sortable-${newAlbumIndex}" class="clearfix data-album sortui ui-sortable sort-drag">
                                </ul>
                            </div>
                        </div>
                        <textarea class="mt20 form-control" name="extract_album[${newAlbumIndex}][description]" placeholder="Nhập mô tả...."></textarea>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        $('.album').last().after(html);
        $( ".sort-drag" ).sortable();
		$( ".sort-drag" ).disableSelection();
    });
    
    $(document).off('click.uploadPicture').on('click.uploadPicture', '.upload-picture', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        let albumIndex = $(this).data('album') || 0;
        BrowseServerExtractAlbum($(this), 'extract_album', albumIndex, 'Images');
    });
}


function BrowseServerAlbum1(object, type){
    var finder = new CKFinder();
    finder.resourceType = type;

    finder.selectActionFunction = function(fileUrl , data, allFiles ) {
        if(typeof(type) == 'undefined'){
            type = 'Images';
        }
        var files = allFiles;
        let album = [];
        for(var i = 0 ; i < files.length; i++){
            fileUrl =  files[i].url.replace(BASE_URL, "/");
            album.push(fileUrl);
        }
        var formURL = 'ajax/slide/echoview';
        $.post(formURL, {
            file: album, count: count
        },
        function(data){
            let json = JSON.parse(data);;
            $('#sortable').append(json.html);

        });

        $('.click-to-upload').hide();
        $('.upload-list').show();
    }
    finder.popup();
}

function BrowseServerAlbumModal(object, id , type){
    var finder = new CKFinder();
    finder.resourceType = type;
    finder.selectActionFunction = function(fileUrl , data, allFiles ) {
        if(typeof(type) == 'undefined'){
            type = 'Images';
        }

        let data_id = object.attr('data-id')
        var files = allFiles;
        var li = '';
        let album  = [];
        for(var i = 0 ; i < files.length; i++){
            fileUrl =  files[i].url.replace(BASE_URL, "/");
            album.push(fileUrl);
            if( i == 0){
                console.log(1)
                $(data_id).find('img').attr('src', fileUrl)
            }
            li = li + '<li class="ui-state-default">';
                li = li + '<div class="thumb">';
                    li = li + '<span class="image img-scaledown img-model">';
                        li = li + '<img src="'+fileUrl+'" alt="">';
                    li = li + '</span>';
                    li = li + '<div class="overlay"></div><div class="delete-image del_img_modal" data-id="#'+id+'"><i class="fa fa-trash" aria-hidden="true"></i></div>';
                li = li + '</div>';
            li = li + '</li>';
        }

        var myJSON = JSON.stringify(album);
        object.parents('.modal').find('.sort-modal').append(li);
        object.parents('.modal').find('.click-to-upload').hide();
        object.parents('.modal').find('.upload-list').show();
        let val = $(data_id).find('.input_img_version').val();
        let value  = "";
        if(val != undefined){
            let value = value_handling(val)
        }
        console.log(value)

        if(value != ''){
            myJSON = myJSON.split('[')
        }
        $(data_id).find('.input_img_version').val(value +((value == "") ? '' : ',')+ ((value == "") ? myJSON : myJSON[1]));
    }
    finder.popup();
}

function value_handling(value){
    let end = value.split("]");
    let result = end[0];
    return result;
}

function BrowseServerProduct(object,id , type){
    var finder = new CKFinder();
    finder.resourceType = type;

    finder.selectActionFunction = function(fileUrl , data, allFiles ) {
        if(typeof(type) == 'undefined'){
            type = 'Images';
        }

        var li = '';
        var files = allFiles;
        let album  = [];
        for(var i = 0 ; i < files.length; i++){
            fileUrl =  files[i].url.replace(BASE_URL, "/");
            album.push(fileUrl);
            if(i == 0){
                object.attr('src', fileUrl);
            }
        }
        var myJSON = JSON.stringify(album);
        let val = object.parents('tr').find('.input_img_version').val();
        let value  = "";
        if(val != undefined){
            let value = value_handling(val)
        }
        if(value != ''){
            myJSON = myJSON.split('[')
        }
        object.parents('tr').find('.input_img_version').val(value + ((value == "") ? '' : ',')+ ((value == "") ? myJSON : myJSON[1]));
    }
    finder.popup();
}


function BrowseServerPreview  (object, type){
	if(typeof(type) == 'undefined'){
		type = 'Images';
	}
	var finder = new CKFinder();
	finder.resourceType = type;
	 finder.selectActionFunction = function( fileUrl, data ) {
        fileUrl =  fileUrl.replace(BASE_URL, "/");
        object.parent().siblings('input').val(fileUrl)
        fileUrl = process_img_link_mp4(fileUrl);
        object.attr('src', fileUrl);
    }
    finder.popup();
}

const editors = {};
function ckeditor5(elementId){
    CKEDITOR.replace( elementId, {
            height: 250,
            removeButtons: '',
            entities: true,
            allowedContent: true,
            toolbarGroups: [
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'colors' },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' }
            ],
        });
}



function process_img_link_mp4(url = ''){
    let result = url;
    let pos = url.indexOf('.mp4');
    if (url.length > 0 && pos >= 0) {
        result = _THUMB_VIDEO;
    }

    return result;
}