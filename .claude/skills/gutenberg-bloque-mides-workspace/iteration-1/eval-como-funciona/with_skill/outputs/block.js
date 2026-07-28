(function(){
  var el=wp.element.createElement,useBlockProps=wp.blockEditor.useBlockProps,InspectorControls=wp.blockEditor.InspectorControls;
  var PanelBody=wp.components.PanelBody,TextControl=wp.components.TextControl,TextareaControl=wp.components.TextareaControl,Button=wp.components.Button;

  wp.blocks.registerBlockType('mides/como-funciona',{
    edit:function(props){
      var a=props.attributes,s=props.setAttributes,pasos=a.pasos;
      var bp=useBlockProps({className:'mides-como-funciona-editor'});

      function setPaso(i,key,val){
        s({pasos:pasos.map(function(x,j){if(j!==i)return x;var c=Object.assign({},x);c[key]=val;return c;})});
      }
      function removePaso(i){
        if(pasos.length<=3)return; // mínimo 3 pasos
        s({pasos:pasos.filter(function(_,j){return j!==i;})});
      }
      function addPaso(){
        if(pasos.length>=5)return; // máximo 5 pasos
        var n=String(pasos.length+1).padStart(2,'0');
        s({pasos:pasos.concat([{numero:n,titulo:'',descripcion:''}])});
      }

      return el('div',bp,
        el(InspectorControls,null,
          el(PanelBody,{title:'Título de sección',initialOpen:true},
            el(TextControl,{label:'Título',value:a.titulo,onChange:function(v){s({titulo:v});}})
          ),
          el(PanelBody,{title:'Pasos (mín. 3 — máx. 5)',initialOpen:true},
            pasos.map(function(paso,i){
              return el('div',{key:i,className:'mides-panel-item'},
                el('p',{className:'mides-panel-item__label'},paso.numero+'. '+(paso.titulo||'Sin título')),
                el(TextControl,{label:'Número',value:paso.numero,onChange:function(v){setPaso(i,'numero',v);}}),
                el(TextControl,{label:'Título del paso',value:paso.titulo,onChange:function(v){setPaso(i,'titulo',v);}}),
                el(TextareaControl,{label:'Descripción',value:paso.descripcion,rows:2,onChange:function(v){setPaso(i,'descripcion',v);}}),
                pasos.length>3
                  ? el(Button,{onClick:function(){removePaso(i);},variant:'tertiary',isDestructive:true,style:{fontSize:'11px',marginTop:'4px'}},'Eliminar')
                  : el('p',{style:{fontSize:'11px',color:'#718096',marginTop:'4px'}},'Mínimo 3 pasos')
              );
            }),
            pasos.length<5
              ? el(Button,{onClick:addPaso,variant:'secondary',style:{marginTop:'8px',width:'100%'}},'+ Agregar paso')
              : el('p',{style:{fontSize:'11px',color:'#718096',textAlign:'center',marginTop:'8px'}},'Máximo 5 pasos alcanzado')
          )
        ),
        el('div',{className:'mides-editor-preview'},
          el('h2',{className:'mides-editor-preview__title'},a.titulo),
          el('div',{className:'mides-como-funciona-editor__grid'},
            pasos.map(function(paso,i){
              return el('div',{key:i,className:'mides-como-funciona-editor__paso'},
                el('div',{className:'mides-como-funciona-editor__numero'},paso.numero),
                el('h3',{className:'mides-como-funciona-editor__paso-titulo'},paso.titulo||'Título del paso'),
                el('p',{className:'mides-como-funciona-editor__paso-desc'},paso.descripcion||'Descripción del paso...')
              );
            })
          )
        )
      );
    },
    save:function(){return null;}
  });
})();
