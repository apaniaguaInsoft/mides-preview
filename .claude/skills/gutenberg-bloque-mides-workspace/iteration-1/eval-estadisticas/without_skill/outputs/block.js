(function(){
  var el=wp.element.createElement,useBlockProps=wp.blockEditor.useBlockProps,InspectorControls=wp.blockEditor.InspectorControls,MediaUpload=wp.blockEditor.MediaUpload,MediaUploadCheck=wp.blockEditor.MediaUploadCheck;
  var PanelBody=wp.components.PanelBody,TextControl=wp.components.TextControl,Button=wp.components.Button;
  var useSelect=wp.data.useSelect;

  // Componente que resuelve el URL del ícono desde la librería de medios
  function IconoPreview(props){
    var media=useSelect(function(select){
      return props.iconoId ? select('core').getMedia(props.iconoId) : null;
    },[props.iconoId]);
    var src=(media&&media.source_url)||props.iconoUrl||'';
    if(!src) return props.placeholder||null;
    return el('img',{src:src,style:props.style,className:props.className||''});
  }

  wp.blocks.registerBlockType('mides/estadisticas-ministerio',{
    edit:function(props){
      var a=props.attributes,s=props.setAttributes,items=a.estadisticas;
      var bp=useBlockProps({className:'mides-estadisticas-editor'});

      function setItem(i,key,val){
        s({estadisticas:items.map(function(x,j){if(j!==i)return x;var c=Object.assign({},x);c[key]=val;return c;})});
      }
      function removeItem(i){s({estadisticas:items.filter(function(_,j){return j!==i;})});}
      function addItem(){
        if(items.length>=6) return;
        s({estadisticas:items.concat([{numero:'0',etiqueta:'Nueva estadística',iconoId:0,iconoUrl:'',iconoAlt:''}])});
      }

      return el('div',bp,
        el(InspectorControls,null,
          el(PanelBody,{title:'Título de sección',initialOpen:true},
            el(TextControl,{
              label:'Título',
              value:a.titulo,
              onChange:function(v){s({titulo:v});}
            })
          ),
          el(PanelBody,{title:'Estadísticas',initialOpen:true},
            items.map(function(x,i){
              return el('div',{key:i,className:'mides-panel-item'},
                el('p',{className:'mides-panel-item__label'},(i+1)+'. '+(x.etiqueta||'Sin etiqueta')),
                el(TextControl,{label:'Número / cifra',value:x.numero,onChange:function(v){setItem(i,'numero',v);}}),
                el(TextControl,{label:'Etiqueta',value:x.etiqueta,onChange:function(v){setItem(i,'etiqueta',v);}}),
                el('span',{className:'mides-editor-media-label'},'Ícono SVG'),
                el(IconoPreview,{
                  iconoId:x.iconoId,iconoUrl:x.iconoUrl,
                  style:{width:'32px',height:'32px',objectFit:'contain',display:'block',margin:'4px 0'}
                }),
                el(MediaUploadCheck,null,el(MediaUpload,{
                  onSelect:function(m){setItem(i,'iconoId',m.id);setItem(i,'iconoUrl',m.url);setItem(i,'iconoAlt',m.alt||x.etiqueta);},
                  allowedTypes:['image'],
                  value:x.iconoId||undefined,
                  render:function(r){return el(Button,{onClick:r.open,variant:'secondary',style:{fontSize:'11px',marginBottom:'4px'}},x.iconoId?'Cambiar ícono':'Subir ícono');}
                })),
                el(Button,{onClick:function(){removeItem(i);},variant:'tertiary',isDestructive:true,style:{fontSize:'11px',marginTop:'4px'}},'Eliminar')
              );
            }),
            items.length<6
              ? el(Button,{onClick:addItem,variant:'secondary',style:{marginTop:'8px',width:'100%'}},'+ Agregar estadística')
              : el('p',{style:{fontSize:'11px',color:'#718096',textAlign:'center',margin:'8px 0 0'}},'Máximo 6 estadísticas')
          )
        ),

        /* ── Preview visual en el canvas ───────────────────────────────── */
        el('div',{className:'mides-editor-preview mides-editor-preview--dark'},
          el('h2',{className:'mides-editor-preview__title mides-estadisticas-editor__title'},a.titulo),
          el('div',{className:'mides-estadisticas-editor__grid'},
            items.map(function(x,i){
              return el('div',{key:i,className:'mides-estadisticas-editor__card'},
                x.iconoId
                  ? el(IconoPreview,{
                      iconoId:x.iconoId,iconoUrl:x.iconoUrl,
                      className:'mides-estadisticas-editor__icono',
                      style:{width:'40px',height:'40px',objectFit:'contain',display:'block',margin:'0 auto 8px'}
                    })
                  : el('div',{className:'mides-editor-avatar-placeholder mides-estadisticas-editor__icono-placeholder'},'SVG'),
                el('span',{className:'mides-estadisticas-editor__numero'},x.numero),
                el('p',{className:'mides-estadisticas-editor__etiqueta'},x.etiqueta)
              );
            })
          )
        )
      );
    },
    save:function(){return null;}
  });
})();
