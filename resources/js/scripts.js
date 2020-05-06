$('document').ready(function () {
    //Delete project
    $('#delete-project-modal').on('show.bs.modal',function (e) {
        $(this).find('#project_id_delete').text($(e.relatedTarget).data('project'));
    });

    $('#project_delete_btn').on('click',function () {
        var id = $('#delete-project-modal').find('#project_id_delete').text();
        var url = $('#delete-project-modal').find('#project_url_delete').text()+'/'+id;
        window.location.replace(url);
    });

    //Add locality modal
    $('#add-locality-form').submit(function () {
        if($('#locality_project_id').val()==''){
            $('#locality_project_id').focus();
            return false;
        }
        if($('#locality_name').val().trim().length==0){
            $('#locality_name').focus();
            return false;
        }
    });

    //Edit locality modal
    $('#edit-locality-modal').on('show.bs.modal',function (e){
        var id = $(e.relatedTarget).data('locality');
        var project = $(e.relatedTarget).data('project');
        var name = $(e.relatedTarget).data('name');

        var modal = $(this);
        modal.find('#locality_id_edit').val(id);
        modal.find('#locality_name_edit').val(name);
        modal.find('#locality_project_id_edit').val(project);
    });

    $('#edit-locality-form').submit(function () {

        var modal = $('#edit-locality-modal');
        var id = modal.find('#locality_id_edit').val();
        var name = modal.find('#locality_name_edit').val();
        var project = modal.find('#locality_project_id_edit').val();
        var url = modal.find('#url').text();
        var token = modal.find('#token').text();

        if (name.trim().length==0){
            modal.find('#locality_name_edit').focus();
            return false;
        }
        if(project == ''){
            modal.find('#locality_project_id_edit').focus();
            return false;
        }
        $.ajax({
            url: url,
            type: 'POST',
            data:{
                id:id,
                name:name,
                project:project
            },
            headers: {
                'X-CSRF-Token': token
            }
        }).done(function (data) {
            modal.hide();
            window.location.reload();
        });
        return false;
    });
    //Delete Locality
    $('#delete-locality-modal').on('show.bs.modal',function (e) {
        $(this).find('#locality_id_delete').text($(e.relatedTarget).data('locality'));
    });

    $('#locality_delete_btn').on('click',function () {
        var id = $('#delete-locality-modal').find('#locality_id_delete').text();
        var url = $('#delete-locality-modal').find('#locality_url_delete').text()+'/'+id;
        // alert(url);
        // return;
        window.location.replace(url);
    });


    //Block index
    //Choose locality
    $('.block_project_id').on('change',function () {
        var project = $(this).val();
        var url = $('#add-block-modal').find('#url').text();
        var token = $('#add-block-modal').find('.token').text();
        if(project.trim().length!=0){
            $.ajax({
                url: url,
                type: 'POST',
                data:{
                    project:project
                },
                headers: {
                    'X-CSRF-Token': token
                }
            }).done(function (data) {
                if(data){
                    console.log(data);
                    $('.block_locality_id').empty();
                    $.each(data,function (index,value) {
                        $('.block_locality_id').append("<option value='"+index+"'>"+value+"</option>");
                    });

                    $('.block_locality_id').removeAttr('disabled');
                }
            });
        }else{
            $('.block_locality_id').empty();
            $('.block_locality_id').append("<option value=''>Select locality</option>");
        }
    });

    //Add block form
    $('#add-block-form').submit(function () {
        var name = $('#add-block-modal').find('#block_name').val();
        var project = $('#add-block-modal').find('#block_project_id').val();
        var locality = $('#add-block-modal').find('.block_locality_id').val();
        var surveyNumber = $('#add-block-modal').find('#block_survey_number').val();

        if(name.trim().length==0){
            $('#add-block-modal').find('#block_name').focus();
            return false;
        }
        if (surveyNumber.trim().length==0){
            $('#add-block-modal').find('#block_survey_number').focus();
            return false;
        }
        if (project=='') {
            $('#add-block-modal').find('#block_project_id').focus();
            return false;
        }
        if (locality==''){
            $('#add-block-modal').find('#block_locality_id').focus();
            return false;
        }
    });
    
    //Edit block form
    $('#edit-block-modal').on('show.bs.modal',function (e) {
        var block = $(e.relatedTarget).data('block');
        var code = $(e.relatedTarget).data('code');
        var project = $(e.relatedTarget).data('project');
        var locality = $(e.relatedTarget).data('locality');
        var surveyNumber = $(e.relatedTarget).data('survey');

        $(this).find('#block_id_edit').val(block);
        $(this).find('#block_code_edit').val(code);
        $(this).find('.block_project_id').val(project);
        $(this).find('.block_locality_id').val(locality);
        $(this).find('#block_survey_number_edit').val(surveyNumber);


        var form = $('#edit-block-form');
        var fction = $(this).find('#updateUrl').text();
        form[0].action = fction+'/'+block;

        var url = $('#edit-block-modal').find('#url').text();
        var token = $('#edit-block-modal').find('.token').text();

            $.ajax({
                url: url,
                type: 'POST',
                data:{
                    project:project
                },
                headers: {
                    'X-CSRF-Token': token
                }
            }).done(function (data) {
                if(data){
                    $('.block_locality_id').empty();
                    $.each(data,function (index,value) {
                        $('.block_locality_id').append("<option value='"+index+"'>"+value+"</option>");
                    });

                    $('.block_locality_id').removeAttr('disabled');
                }
            });
    });

    //Edit form submit
    $('#edit-block-form').submit(function () {
        var code = $('#edit-block-modal').find('#block_code_edit').val();
        var project = $('#edit-block-modal').find('.block_project_id').val();
        var locality = $('#edit-block-modal').find('.block_locality_id').val();
        var surveyNumber = $('#edit-block-modal').find('#block_survey_number_edit').val();

        if(code.trim().length==0){
            $('#edit-block-modal').find('#block_code_edit').focus();
            return false;
        }
        if (surveyNumber.trim().length==0){
            $('#edit-block-modal').find('#block_survey_number_edit').focus();
            return false;
        }
        if (project=='') {
            $('#edit-block-modal').find('.block_project_id').focus();
            return false;
        }
        if (locality==''){
            $('#edit-block-modal').find('.block_locality_id').focus();
            return false;
        }
    });

    //Delete Block
    $('#delete-block-modal').on('show.bs.modal',function (e) {
        $(this).find('#block_id_delete').text($(e.relatedTarget).data('block'));
    });

    $('#block_delete_btn').on('click',function () {
        var id = $('#delete-block-modal').find('#block_id_delete').text();
        var url = $('#delete-block-modal').find('#block_url_delete').text()+'/'+id;
        // alert(url);
        // return;
        window.location.replace(url);
    });
});