// Se define un nuevo componente llamado nav-bar-component
Vue.component('nav-component', {
  template: `
    <nav class="navbar navbar-dark navbar-expand-lg navbar-light bg-dark">
    <a class="navbar-brand" href="#">{Anonimo}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#"><i class="fas fa-home "></i><span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Dropdown
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>
  `
});

const navTemplate = new Vue({
  el: '#app-navbar'
});

const tituloTemplate = new Vue({
  el: '#tituloTemplate',
  template: `
      <h1 class="text-center text-light">{{ tituloPagina }}</h1>
  `,
  data: {
  	tituloPagina: "Bienvenidos",
  }  
});

const app = new Vue({
  el: '#app',
  data: {
  	mostrar: true,
  	classBtnIMG: "btn btn-outline-success",
  	msjMostrar: "Ocultar",
    mensaje: "Inicio",
    imagen: "resources/img/tecnologias/BraveViciousDeviltasmanian-size_restricted.gif",
    tecnologias: [
      { id:"1", name: "Vue JS", logo: 'resources/img/tecnologias/vuejs-wide.png' },
      { id:"2", name: "JQuery", logo: 'resources/img/tecnologias/single_jquery-logo.jpg' },
      { id:"3", name: "Ajax", logo: 'resources/img/tecnologias/ajax-logo.jpg' },
      { id:"4", name: "Bootstrap", logo: 'resources/img/tecnologias/bootstrap-4.png' },
    ],
    usuarios: []
  },
  methods: {
    metodoMostrar: function () 
    {

      this.mostrar = !this.mostrar;
      
      if(this.mostrar)
      {
      	this.msjMostrar = "Ocultar";
      	this.classBtnIMG = "btn btn-outline-success";
      }
      else
      {
      	this.msjMostrar = "Mostrar";
      	this.classBtnIMG = "btn btn-outline-primary";
      }

    },
    getUsuarios: function(){

       axios.get('php/router/ProjVueRouter.php', {
          params: {
            opcion: 2,
            modulo: "prueba"
          }
        }).then(function (response) {
            console.log(response.data);
            if (response.data.CodRespuesta == 1) 
            {
              this.usuarios = response.data.infoResponse;
              console.log(usuarios);
            }
        })
        .catch(function (error) {
            console.log(error);
        });
    }
  }
});