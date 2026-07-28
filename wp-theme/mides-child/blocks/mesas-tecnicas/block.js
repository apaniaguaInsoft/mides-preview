(function(){
  var el=wp.element.createElement,useBlockProps=wp.blockEditor.useBlockProps,InspectorControls=wp.blockEditor.InspectorControls;
  var PanelBody=wp.components.PanelBody,TextControl=wp.components.TextControl,TextareaControl=wp.components.TextareaControl,Button=wp.components.Button;

  wp.blocks.registerBlockType('mides/mesas-tecnicas',{
    edit:function(props){
      var a=props.attributes,s=props.setAttributes,mesas=a.mesas;
      var bp=useBlockProps({className:'mides-mesas-editor'});

      function setMesa(i,key,val){
        s({mesas:mesas.map(function(x,j){if(j!==i)return x;var c=Object.assign({},x);c[key]=val;return c;})});
      }
      function removeMesa(i){s({mesas:mesas.filter(function(_,j){return j!==i;})});}
      function addMesa(){var n=String(mesas.length+1).padStart(2,'0');s({mesas:mesas.concat([{num:n,titulo:'',rectora:'',descripcion:''}])});}

      return el('div',bp,
        el(InspectorControls,null,
          el(PanelBody,{title:'Encabezado',initialOpen:true},
            el(TextControl,{label:'Título',value:a.titulo,onChange:function(v){s({titulo:v});}}),
            el(TextareaControl,{label:'Texto introductorio',value:a.intro,rows:3,onChange:function(v){s({intro:v});}}),
            el(TextControl,{label:'URL Reuniones Ordinarias',value:a.urlOrdinarias,onChange:function(v){s({urlOrdinarias:v});}}),
            el(TextControl,{label:'URL Reuniones Extraordinarias',value:a.urlExtraordinarias,onChange:function(v){s({urlExtraordinarias:v});}})
          ),
          el(PanelBody,{title:'Etiquetas',initialOpen:false},
            el(TextControl,{label:'Prefijo entidad rectora',value:a.labelRectora,onChange:function(v){s({labelRectora:v});}}),
            el(TextControl,{label:'Botón ordinarias — título',value:a.labelOrdinarias,onChange:function(v){s({labelOrdinarias:v});}}),
            el(TextControl,{label:'Botón ordinarias — subtexto',value:a.textoOrdinarias,onChange:function(v){s({textoOrdinarias:v});}}),
            el(TextControl,{label:'Botón extraordinarias — título',value:a.labelExtraordinarias,onChange:function(v){s({labelExtraordinarias:v});}}),
            el(TextControl,{label:'Botón extraordinarias — subtexto',value:a.textoExtraordinarias,onChange:function(v){s({textoExtraordinarias:v});}})
          ),
          el(PanelBody,{title:'Mesas Técnicas',initialOpen:false},
            mesas.map(function(m,i){
              return el('div',{key:i,className:'mides-mesas-panel'},
                el('p',{className:'mides-mesas-panel__num'},m.num+' '+m.titulo),
                el(TextControl,{label:'Número',value:m.num,onChange:function(v){setMesa(i,'num',v);}}),
                el(TextControl,{label:'Título',value:m.titulo,onChange:function(v){setMesa(i,'titulo',v);}}),
                el(TextControl,{label:'Entidad rectora',value:m.rectora,onChange:function(v){setMesa(i,'rectora',v);}}),
                el(TextareaControl,{label:'Descripción',value:m.descripcion,rows:2,onChange:function(v){setMesa(i,'descripcion',v);}}),
                el(Button,{onClick:function(){removeMesa(i);},variant:'tertiary',isDestructive:true,style:{fontSize:'11px'}},'Eliminar')
              );
            }),
            el(Button,{onClick:addMesa,variant:'secondary',style:{marginTop:'8px',width:'100%'}},'+ Agregar mesa')
          )
        ),
        el('div',{className:'mides-mesas-editor__header'},
          el('h2',{className:'mides-mesas-editor__title'},a.titulo),
          el('p',{className:'mides-mesas-editor__intro'},a.intro)
        ),
        el('div',{className:'mides-mesas-editor__grid'},
          mesas.map(function(m,i){
            return el('div',{key:i,className:'mides-mesas-editor__card'},
              el('div',{className:'mides-mesas-editor__num'},m.num),
              el('p',{className:'mides-mesas-editor__card-title'},m.titulo),
              el('p',{className:'mides-mesas-editor__rectora'},m.rectora)
            );
          })
        )
      );
    },
    save:function(){return null;}
  });
})();
