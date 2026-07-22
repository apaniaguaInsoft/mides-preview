(function(){
  var el=wp.element.createElement,useBlockProps=wp.blockEditor.useBlockProps,InspectorControls=wp.blockEditor.InspectorControls,MediaUpload=wp.blockEditor.MediaUpload,MediaUploadCheck=wp.blockEditor.MediaUploadCheck;
  var PanelBody=wp.components.PanelBody,TextControl=wp.components.TextControl,TextareaControl=wp.components.TextareaControl,ToggleControl=wp.components.ToggleControl,Button=wp.components.Button;

  wp.blocks.registerBlockType('mides/about-block',{
    edit:function(props){
      var a=props.attributes,s=props.setAttributes;
      var cls='mides-about-editor'+(a.fondoAlt?' mides-about-editor--alt':'');
      var bp=useBlockProps({className:cls});
      var imgCol=el('div',null,
        a.imagenUrl
          ? el('img',{src:a.imagenUrl,className:'mides-about-editor__img'})
          : el('div',{className:'mides-about-editor__img-placeholder'},'Sin imagen'),
        el(MediaUploadCheck,null,el(MediaUpload,{
          onSelect:function(m){s({imagenId:m.id,imagenUrl:m.url,imagenAlt:m.alt||''});},
          allowedTypes:['image'],value:a.imagenId,
          render:function(r){return el(Button,{onClick:r.open,variant:'secondary',style:{marginTop:'6px',fontSize:'11px'}},a.imagenId?'Cambiar imagen':'Subir imagen');}
        }))
      );
      var textCol=el('div',null,
        el('h3',{className:'mides-about-editor__title'},a.titulo||'Sin título'),
        el('p',{className:'mides-about-editor__body'},a.cuerpo)
      );
      return el('div',bp,
        el(InspectorControls,null,el(PanelBody,{title:'Contenido',initialOpen:true},
          el(TextControl,{label:'Título',value:a.titulo,onChange:function(v){s({titulo:v});}}),
          el(TextareaControl,{label:'Texto (doble salto de línea = nuevo párrafo)',value:a.cuerpo,rows:8,onChange:function(v){s({cuerpo:v});}}),
          el(ToggleControl,{label:'Imagen a la izquierda',checked:a.reverso,onChange:function(v){s({reverso:v});}}),
          el(ToggleControl,{label:'Fondo gris alternativo',checked:a.fondoAlt,onChange:function(v){s({fondoAlt:v});}})
        )),
        el('div',{className:'mides-about-editor__grid'},
          a.reverso ? [imgCol,textCol] : [textCol,imgCol]
        )
      );
    },
    save:function(){return null;}
  });
})();
