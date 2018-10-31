$(document).ready(function(){
    getCustomcharDefault();

    $('#id_customchar1').bind('change',function(){
        var q = '';

        $("input[name='customchar1']").val($('#id_customchar1').val());
        if($('#id_customchar1').val() != 0){
            q = $('#id_customchar1').val();
        }

        if(q == '' || q == 0){
            $('#id_customchar2').empty().append('<option value="0">All</option>');
            $("input[name='customchar2']").val(0);
            return false;
        }

        $.ajax({
            type: "post",
            url: "faculty.php",
            data: "q=" + q,
            dataType: "json",
            beforeSend: function(XMLHttpRequest){},
            success: function(data, textStatus){
                $('#id_customchar2').empty().append('<option value="0">All</option>');
                $.each(data,function(key,val){
                    if(key <= 0) { customchar2_val = $("input[name='customchar2']").val(val);
                    }
                    $('#id_customchar2').append('<option value="' + val + '">' + val + '</option>');
                });
            }
        })
    });

    $('#id_customchar2').bind('change',function(){
        $("input[name='customchar2']").val($('#id_customchar2').val());
    });
});

function getCustomcharDefault(){
    var q = $("input[name='customchar1']").val();
    var customchar2_val = $("input[name='customchar2']").val();
    if(q){
        $.ajax({
            type: "post",
            url: "faculty.php",
            data: "q=" + q,
            dataType: "json",
            beforeSend: function(XMLHttpRequest){},
            success: function(data, textStatus){
                $('#id_customchar2').empty().append('<option value="0">All</option>');
                $.each(data,function(key,val){
                    if(customchar2_val == val){
                        $('#id_customchar2').append('<option value="' + val + '" selected>' + val + '</option>');
                    }else{
                        $('#id_customchar2').append('<option value="' + val + '">' + val + '</option>');
                    }
                });
            }
        })
    }
}