(function(){
  var el=wp.element.createElement,useBlockProps=wp.blockEditor.useBlockProps,InspectorControls=wp.blockEditor.InspectorControls,PanelBody=wp.components.PanelBody,TextControl=wp.components.TextControl;
  wp.blocks.registerBlockType('mides/hero-interior',{
    edit:function(props){
      var a=props.attributes,s=props.setAttributes,bp=useBlockProps({className:'mides-hero-interior-editor'});
      return el('div',bp,
        el(InspectorControls,null,el(PanelBody,{title:'Contenido',initialOpen:true},
          el(TextControl,{label:'Título',value:a.titulo,onChange:function(v){s({titulo:v});}}),
          el(TextControl,{label:'Subtítulo',value:a.subtitulo,onChange:function(v){s({subtitulo:v});}})
        )),
        el('p',{className:'mides-hero-interior-editor__title'},a.titulo),
        a.subtitulo&&el('p',{className:'mides-hero-interior-editor__sub'},a.subtitulo)
      );
    },
    save:function(){return null;}
  });
})();
