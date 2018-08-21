
$(function(){
    _checked({
        parent:'.j-parent',
        son:'.j-son',
        grandson:'.j-grandson'
    });
});

//_checked({
//    parent:'.pid',
//    son:'.son',
//    grandson:'.grandson'
//});
function _checked(obj) {
    /*  html 正确 结构
        <div class="最大的盒子">

            <div class="父亲盒子 j-parent"><input name="父亲" type="checkbox" />&nbsp;父亲</div>

            <div class="儿子盒子 j-son">
                <input name="儿子" type="checkbox" />&nbsp;儿子
                <br>
                <div class="孙子盒子 j-grandson">
                    <input name="孙子" type="checkbox" />&nbsp;孙子
                </div>
                <br>
            </div>

        </div>
     */
     var arr = [];  //用来判断父亲
     var son_arr = []; //用来判断孙子
     $(obj.parent+' input').on('click',function() {
        var $parent   = $(this).parent();
        var $son      = $parent.nextAll(obj.son);
        var $grandson = $son.find(obj.grandson);
        $son.children('input').prop('checked', $(this).prop('checked') );
        $grandson.children('input').prop('checked', $(this).prop('checked') );
     }).parent().each(function(i, el) {
         arr[i] = [];
         son_arr[i] = [];
     });;

     //点击儿子
     $(obj.son+' input').on('click',function() {
        var $parent   = $(this).parent();
        //var $grandson = $parent.find(obj.grandson);
        //$grandson.children('input').prop('checked', $(this).prop('checked') );
        var i = $parent.parent().parent().index();
        //console.log( i );
        $parent.parent().children(obj.son).each(function(index, el) {
           arr[i][index] = $(this).children('input').prop('checked');
        });
        //console.log(  arr[i] )
        for(var x in arr[i] ){
            if( arr[i][x] == true ){
                $parent.parent().children(obj.parent).children('input').prop('checked',true);
                return;
            }
        }
        $parent.parent().children(obj.parent).children('input').prop('checked',false);
     });

     //点击孙子
     $(obj.grandson+' input').on('click',function() {
        var $grandson  = $(this).parent();
        var $son = $grandson.parent();
        var $pid = $son.siblings(obj.parent);

        $grandson[0].arr = [];
        $grandson.children('input').each(function(i, el) {
            $grandson[0].arr[i] = $(this).prop('checked');
        });

        $pid[0].arr = [];
        for (var i=0; i<$grandson[0].arr.length; i++) {
            if( $grandson[0].arr[i] == true ){
                //alert('被勾选了');
                $son.children('input').prop('checked', true );
                $pid.children('input').prop('checked', true ).nextAll(obj.son).each(function(i, el) {
                    $pid[0].arr[i] = $(this).prop('checked');
                });
                for (var i=0; i< $pid[0].arr.length; i++) {
                    if( $pid[0].arr[i] == true ){
                        //alert('被勾选了');
                        $pid.children('input').prop('checked', true )
                    }
                    return;
                };
                return;
            }
        };
        $son.children('input').prop('checked', false );

        var i = $(this).parent().parent().parent().index();

        // $(this).parent().parent().parent().find('.grandson').each(function(index, el) {
        //     son_arr[i][index] = $(this).children('input').prop('checked');
        // })
        $(this).parent().parent().parent().find(obj.son+' input').each(function(index, el) {
            son_arr[i][index] = $(this).prop('checked');
        })
        console.log( son_arr[i] )
        for( var x in son_arr[i] ){
            var xx = 0;
            if( son_arr[i][x] == true ){
                $pid.children('input').prop('checked', true );
                return;
            }
         }
        // setTimeout(function() {
            $pid.children('input').prop('checked', false );
        // },30);
     });
}