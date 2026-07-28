(function(){
  var el=wp.element.createElement,useBlockProps=wp.blockEditor.useBlockProps,InspectorControls=wp.blockEditor.InspectorControls,MediaUpload=wp.blockEditor.MediaUpload,MediaUploadCheck=wp.blockEditor.MediaUploadCheck;
  var PanelBody=wp.components.PanelBody,TextControl=wp.components.TextControl,Button=wp.components.Button;
  var useSelect=wp.data.useSelect;

  function FotoPreview(props){
    var media=useSelect(function(select){
      return props.fotoId ? select('core').getMedia(props.fotoId) : null;
    },[props.fotoId]);
    var src=(media&&media.source_url)||props.fotoUrl||'';
    if(!src) return props.placeholder||null;
    return el('img',{src:src,style:props.style,className:props.className||''});
  }

  wp.blocks.registerBlockType('mides/autoridades',{
    edit:function(props){
      var a=props.attributes,s=props.setAttributes,aut=a.autoridades;
      var bp=useBlockProps({className:'mides-autoridades-editor'});

      function setAut(i,key,val){
        s({autoridades:aut.map(function(x,j){if(j!==i)return x;var c=Object.assign({},x);c[key]=val;return c;})});
      }
      function removeAut(i){s({autoridades:aut.filter(function(_,j){return j!==i;})});}
      function addAut(){s({autoridades:aut.concat([{cargo:'',nombre:'',puesto:'',fotoId:0,fotoUrl:'',fotoAlt:'',cvUrl:''}])});}

      return el('div',bp,
        el(InspectorControls,null,
          el(PanelBody,{title:'Título',initialOpen:true},
            el(TextControl,{label:'Título de sección',value:a.titulo,onChange:function(v){s({titulo:v});}}),
            el(TextControl,{label:'Texto del botón "Hoja de vida"',value:a.textCv,onChange:function(v){s({textCv:v});}})
          ),
          el(PanelBody,{title:'Autoridades',initialOpen:true},
            aut.map(function(x,i){
              return el('div',{key:i,className:'mides-autoridades-panel'},
                el('p',{className:'mides-autoridades-panel__name'},(i+1)+'. '+(x.nombre||'Sin nombre')),
                el(TextControl,{label:'Cargo',value:x.cargo,onChange:function(v){setAut(i,'cargo',v);}}),
                el(TextControl,{label:'Nombre',value:x.nombre,onChange:function(v){setAut(i,'nombre',v);}}),
                el(TextControl,{label:'Puesto',value:x.puesto,onChange:function(v){setAut(i,'puesto',v);}}),

                /* ── Hoja de vida (PDF) ── */
                el('p',{style:{fontSize:'11px',fontWeight:'600',margin:'8px 0 4px'}},'Hoja de vida (PDF)'),
                el('div',{style:{display:'flex',gap:'6px',alignItems:'center',marginBottom:'4px'}},
                  el(MediaUploadCheck,null,el(MediaUpload,{
                    onSelect:function(m){setAut(i,'cvUrl',m.url);},
                    allowedTypes:['application/pdf'],
                    value:undefined,
                    render:function(r){return el(Button,{onClick:r.open,variant:'secondary',style:{fontSize:'11px'}},
                      x.cvUrl ? 'Cambiar PDF' : 'Seleccionar PDF'
                    );}
                  })),
                  x.cvUrl
                    ? el(Button,{onClick:function(){setAut(i,'cvUrl','');},variant:'tertiary',isDestructive:true,style:{fontSize:'11px'}},'Quitar')
                    : null
                ),
                x.cvUrl
                  ? el('p',{style:{fontSize:'10px',color:'#555',wordBreak:'break-all',margin:'0 0 6px'}},x.cvUrl)
                  : el('p',{style:{fontSize:'10px',color:'#999',margin:'0 0 6px'}},'Sin PDF asignado'),
                el(TextControl,{label:'O pegar URL del PDF',value:x.cvUrl,onChange:function(v){setAut(i,'cvUrl',v);},placeholder:'https://...'}),

                /* ── Foto ── */
                el('p',{style:{fontSize:'11px',fontWeight:'600',margin:'4px 0'}},'Foto'),
                el(FotoPreview,{fotoId:x.fotoId,fotoUrl:x.fotoUrl,style:{width:'64px',height:'64px',borderRadius:'50%',objectFit:'cover',display:'block',margin:'4px 0'}}),
                el(MediaUploadCheck,null,el(MediaUpload,{
                  onSelect:function(m){setAut(i,'fotoId',m.id);setAut(i,'fotoUrl',m.url);setAut(i,'fotoAlt',m.alt||x.nombre);},
                  allowedTypes:['image'],value:x.fotoId||undefined,
                  render:function(r){return el(Button,{onClick:r.open,variant:'secondary',style:{fontSize:'11px',marginBottom:'4px'}},x.fotoId?'Cambiar foto':'Subir foto');}
                })),
                el(Button,{onClick:function(){removeAut(i);},variant:'tertiary',isDestructive:true,style:{fontSize:'11px'}},'Eliminar')
              );
            }),
            el(Button,{onClick:addAut,variant:'secondary',style:{marginTop:'8px',width:'100%'}},'+ Agregar autoridad')
          )
        ),
        el('h2',{className:'mides-autoridades-editor__title'},a.titulo),
        el('div',{className:'mides-autoridades-editor__grid'},
          aut.map(function(x,i){
            return el('div',{key:i,className:'mides-autoridades-editor__card'},
              el(FotoPreview,{
                fotoId:x.fotoId,fotoUrl:x.fotoUrl,
                className:'mides-autoridades-editor__foto',
                style:{width:'80px',height:'80px',borderRadius:'50%',objectFit:'cover',display:'block',margin:'0 auto 8px'},
                placeholder:el('div',{className:'mides-autoridades-editor__placeholder'})
              }),
              el('span',{className:'mides-autoridades-editor__cargo'},x.cargo),
              el('p',{className:'mides-autoridades-editor__nombre'},x.nombre),
              el('p',{className:'mides-autoridades-editor__puesto'},x.puesto),
              x.cvUrl
                ? el('span',{className:'mides-autoridades-editor__cv-badge'},a.textCv||'Ver hoja de vida')
                : null
            );
          })
        )
      );
    },
    save:function(){return null;}
  });
})();
