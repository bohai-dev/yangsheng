$(function(){
	$("select[name='type']").on('change', function(){
		change_type($(this).val());
	});
});

function change_type(type_id)
{
	$.ajax({
		url:"admin.php?s=Shop/Goods/getspec",
		type:'POST',
		dataType:'json',
		data:{'type_id':type_id},
		success: function(data){
			if(data.status){
				spec_num = data.spec.length;
				if(spec_num>0){
					html = spec_html(data.spec);
					$('#goods_spec_table1').html(html);
				}else{
					$('#goods_spec_table1').html('');
					$('#goods_spec_table2').html('');
				}
			}else{
				$('#goods_spec_table1').html('');
				$('#goods_spec_table2').html('');
			}
		},
		error: function(e){

		}
	})
}

function spec_html(spec)
{
	html = '';
	$.each(spec, function(k1, v1){
		html+= '<tr>'+
					'<td data-spec_id="'+v1.id+'">'+v1.name+'：</td>'+
					'<td>';
		$.each(v1.items, function(k2, v2){
			html+='<button type="button" data-spec_id="'+v1.id+'" data-item_id="'+v2.id+'" class="btn btn-default" >'+
					v2.item+
				  '</button>&nbsp;&nbsp;&nbsp;';
		});
		html+=		'</td>';
		html+= '</tr>';
	});
	return html;
}