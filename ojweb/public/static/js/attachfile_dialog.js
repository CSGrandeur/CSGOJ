if(!alertify.AttachFile)
{
	alertify.dialog('AttachFile', function factory(){
		return {
			main:function(item, item_id){
				this.item = item;
				this.item_id = item_id;
			},
			setup:function(){
				return {
					buttons:[{text: "cool !", key:27/*Esc*/}],
					focus: { element:0 },
					options: {
						title: 'File Manager for ' + $('#info_item').attr('name') + ' ' + $('#info_item').val(),
						startMaximized: true
					}
				};
			},
			prepare:function(){
				var iframe = document.createElement('iframe');
				iframe.frameBorder = "no";
				iframe.width = "100%";
				iframe.height = "100%";
				// add it to the dialog
//					this.elements.content.appendChild(iframe);
				iframe.src = "/csgoj/admin/filemanager?item="+this.item+"&id="+this.item_id;
				this.setContent(iframe);
			}
		};
	});
}