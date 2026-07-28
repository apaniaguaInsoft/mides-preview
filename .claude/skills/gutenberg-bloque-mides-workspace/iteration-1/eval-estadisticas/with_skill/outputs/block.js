(function(){
  var el=wp.element.createElement,useBlockProps=wp.blockEditor.useBlockProps,InspectorControls=wp.blockEditor.InspectorControls,MediaUpload=wp.blockEditor.MediaUpload,MediaUploadCheck=wp.blockEditor.MediaUploadCheck;
  var PanelBody=wp.components.PanelBody,TextControl=wp.components.TextControl,Button=wp.components.Button;
  var useSelect=wp.data.useSelect;

  // Muestra el ícono usando iconoId desde la librería de medios; cae en iconoUrl si el ID no está listo
  function IconoPreview(props){
    var media=useSelect(function(select){
      return props.iconoId ? select('core').getMedia(props.iconoId) : null;
    },[props.iconoId]);
    var src=(media&&media.source_url)||props.iconoUrl||'';
    if(!src) return props.placeholder||null;
    return el('img',{src:src,style:props.style,className:props.className||''});
  }

  wp.blocks.registerBlockType('mides/estadisticas',{
    edit:function(props){
      var a=props.attributes,s=props.setAttributes,items=a.estadisticas;
      var bp=useBlockProps({className:'mides-estadisticas-editor'});

      function setItem(i,key,val){
        s({estadisticas:items.map(function(x,j){if(j!==i)return x;var c=Object.assign({},x);c[key]=val;return c;})});
      }
      function removeItem(i){s({estadisticas:items.filter(function(_,j){return j!==i;})});}
      function addItem(){
        if(items.length>=6) return;
        s({estadisticas:items.concat([{numero:'',etiqueta:'',iconoId:0,iconoUrl:'',iconoAlt:''}])});
      }

      return el('div',bp,
        el(InspectorControls,null,
          el(PanelBody,{title:'Encabezado',initialOpen:true},
            el(TextControl,{label:'Título de sección',value:a.titulo,onChange:function(v){s({titulo:v});}})
          ),
          el(PanelBody,{title:'Estadísticas (máx. 6)',initialOpen:true},
            items.map(function(item,i){
              return el('div',{key:i,className:'mides-panel-item'},
                el('p',{className:'mides-panel-item__label'},(i+1)+'. '+(item.etiqueta||'Sin etiqueta')),
                el(TextControl,{label:'Número / Cifra',value:item.numero,onChange:function(v){setItem(i,'numero',v);}}),
                el(TextControl,{label:'Etiqueta',value:item.etiqueta,onChange:function(v){setItem(i,'etiqueta',v);}}),
                el('span',{className:'mides-editor-media-label'},'Ícono SVG'),
                el(IconoPreview,{
                  iconoId:item.iconoId,iconoUrl:item.iconoUrl,
                  style:{width:'32px',height:'32px',objectFit:'contain',display:'block',margin:'4px 0',filter:'brightness(0) invert(1)'}
                },
                  !item.iconoId&&!item.iconoUrl ? el('div',{className:'mides-editor-img-placeholder mides-estadisticas-editor__icono-placeholder'},'ícono') : null
                ),
                el(MediaUploadCheck,null,el(MediaUpload,{
                  onSelect:function(m){setItem(i,'iconoId',m.id);setItem(i,'iconoUrl',m.url);setItem(i,'iconoAlt',m.alt||item.etiqueta);},
                  allowedTypes:['image'],value:item.iconoId||undefined,
                  render:function(r){return el(Button,{onClick:r.open,variant:'secondary',style:{fontSize:'11px',marginBottom:'4px'}},item.iconoId?'Cambiar ícono':'Subir ícono');}
                })),
                el(Button,{onClick:function(){removeItem(i);},variant:'tertiary',isDestructive:true,style:{fontSize:'11px',marginTop:'4px'}},'Eliminar')
              );
            }),
            items.length<6 && el(Button,{onClick:addItem,variant:'secondary',style:{marginTop:'8px',width:'100%'}},'+ Agregar estadística')
          )
        ),
        el('div',{className:'mides-editor-preview mides-editor-preview--dark'},
          el('h2',{className:'mides-editor-preview__title'},a.titulo),
          el('div',{className:'mides-estadisticas-editor__grid'},
            items.map(function(item,i){
              return el('div',{key:i,className:'mides-estadisticas-editor__card'},
                el('div',{className:'mides-estadisticas-editor__icono-wrap'},
                  el(IconoPreview,{
                    iconoId:item.iconoId,iconoUrl:item.iconoUrl,
                    className:'mides-estadisticas-editor__icono',
                    style:{width:'40px',height:'40px',objectFit:'contain',display:'block',margin:'0 auto',filter:'brightness(0) invert(1)'}
                  },
                    !item.iconoId&&!item.iconoUrl
                      ? el('div',{className:'mides-editor-img-placeholder mides-estadisticas-editor__icono-placeholder'})
                      : null
                  )
                ),
                el('span',{className:'mides-estadisticas-editor__numero'},item.numero||'—'),
                el('p',{className:'mides-estadisticas-editor__etiqueta'},item.etiqueta||'Etiqueta')
              );
            })
          )
        )
      );
    },
    save:function(){return null;}
  });
})();
