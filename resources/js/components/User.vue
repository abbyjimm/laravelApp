<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Usuarios
                    <button type="button" @click="openModal('people', 'insert')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                    <option value="name">Nombre</option>
                                    <option value="num_document">documento</option>
                                    <option value="telephone">telephone</option>
                                    <option value="email">email</option>
                                </select>
                                <input type="text" v-model="search" @keyup.enter="listPeople(1, search, criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listPeople(1, search, criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre</th>
                                <th>Tipo de documento</th>
                                <th>Número</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="people in arrayPeople" :key="people.id">
                                <td>
                                    <button type="button" @click="openModal('people', 'update', people)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="people.condition">
                                        <button type="button" class="btn btn-danger btn-sm" @click="disableUser(people.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="ableUser(people.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="people.name"></td>
                                <td v-text="people.type_document"></td>   
                                <td v-text="people.num_document"></td>     
                                <td v-text="people.address"></td>      
                                <td v-text="people.telephone"></td>      
                                <td v-text="people.email"></td> 
                                <td v-text="people.user"></td>    
                                <td v-text="people.role"></td>   
                                <td>
                                    <div v-if="people.condition==1">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div>
                                </td>                        
                            </tr>                            
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1, search, criteria)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="changePage(page, search, criteria)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1, search, criteria)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'show':modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="titleModal"></h4>
                        <button type="button" class="close" @click="closeModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre del Cliente">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                <div class="col-md-9">
                                    <select v-model="type_document" class="form-control">
                                        <option value="DNI">DNI</option>
                                        <option value="RUC">RUC</option>
                                        <option value="PASS">PASS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Número</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="num_document" class="form-control" placeholder="Número Documento">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="address" class="form-control" placeholder="Dirección">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="telephone" class="form-control" placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Rol (*)</label>
                                <div class="col-md-9">
                                    <select class="form-control" v-model="idrole">
                                        <option value="0">Seleccione un rol</option>
                                        <option v-for="role in arrayRole" :key="role.id" :value="role.id" v-text="role.name"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Usuario (*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="user" class="form-control" placeholder="Usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Password</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="password" class="form-control" placeholder="Password del usuario">
                                </div>
                            </div>
                            <div v-show="errorPeople" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorShowMsgPeople" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="insertPeople()">Guardar</button>
                        <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updatePeople()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    export default {
        data(){
            return{
                people_id : 0,
                name : '',
                type_document: 'DNI',
                num_document: '',
                address: '',
                telephone: '',
                email: '',
                user: '',
                password: '',
                idrole: 0,
                arrayPeople: [],
                arrayRole: [],
                modal: 0,
                titleModal: '',
                typeAction: 0,
                errorPeople: 0,
                errorShowMsgPeople : [],
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0 
                },
                offset : 3,
                criteria : 'name',
                search : ''
            }
        },
        computed :{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calculate the elements of the pagination
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }
            

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods :{
            listPeople(page, search, criteria){
                let me = this;
                var url = '/user?page=' + page + '&search=' + search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    var resp = response.data;
                    me.arrayPeople= resp.users.data;
                    me.pagination = resp.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRole(){
                let me = this;
                var url = '/role/selectRole';
                axios.get(url).then(function (response) {
                    var resp = response.data;
                    me.arrayRole= resp.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePage(page, search, criteria){
                let me = this;
                //update to current page
                me.pagination.current_page = page;
                //send the request to view the pages's data
                me.listPeople(page, search, criteria);
            },
            insertPeople(){
                if(this.validatePeople()){
                    return;
                }

                let me=this;
                axios.post('/user/insert', {
                    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'telephone': this.telephone,
                    'email': this.email,
                    'user' : this.user,
                    'password' : this.password,
                    'idrole': this.idrole
                }).then(function (response) {
                    me.closeModal();
                    me.listPeople(1,'','name');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            updatePeople(){
                if(this.validatePeople()){
                    return;
                }

                let me=this;
                axios.put('/user/update', {
                    'id': this.people_id,
                    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'telephone': this.telephone,
                    'email': this.email,
                    'user' : this.user,
                    'password' : this.password,
                    'idrole': this.idrole                  
                }).then(function (response) {
                    me.closeModal();
                    me.listPeople(1,'','name');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },           
            validatePeople(){
                this.errorPeople = 0;
                this.errorShowMsgPeople = [];

                if(!this.name) this.errorShowMsgPeople.push("El nombre del cliente no puede estar vacio");
                if(!this.address) this.errorShowMsgPeople.push("EL campo dirección no puede estar vacio");
                if(!this.user) this.errorShowMsgPeople.push("EL campo user no puede estar vacio");
                if(!this.password) this.errorShowMsgPeople.push("EL campo password no puede estar vacio");
                if(this.idrole==0) this.errorShowMsgPeople.push("Debes seleccionar un rol para el usuario");                
                
                if(this.errorShowMsgPeople.length) this.errorPeople = 1;

                return this.errorPeople;            
            },
            closeModal(){
                this.modal = 0;
                this.titleModal = '';
                this.type_document = 'DNI';
                this.num_document = '';
                this.address = '';
                this.telephone = '';
                this.email = '';
                this.user = '';
                this.password = '';
                this.idrole = 0;
                this.errorPeople = 0;
            },
            openModal(model, action, data = []){
                this.selectRole();
                console.log(this.arrayRole);
                switch (model) {
                    case 'people':
                    {
                        switch(action){
                            case 'insert':
                            {
                                this.modal = 1;
                                this.name = '';
                                this.type_document = 'DNI';
                                this.num_document = '';
                                this.address = '';
                                this.telephone = '';
                                this.email = '';
                                this.user = '';
                                this.password = '';
                                this.idrole = 0;
                                this.titleModal = 'Registrar Usuario';
                                this.typeAction = 1;
                                break;
                            }
                            case 'update':
                            {
                                this.modal = 1;
                                this.titleModal = 'Actualizar Usuario';
                                this.typeAction = 2;  
                                this.people_id = data['id'];  
                                this.name = data['name'];
                                this.type_document = data['type_document'];
                                this.num_document = data['num_document'];
                                this.address = data['address'];
                                this.telephone = data['telephone'];
                                this.email = data['email'];  
                                this.user = data['user'];
                                this.password = data['password'];  
                                this.idrole = data['idrole'];                       
                                break;
                            }
                        }
                    }
                }
            },
            disableUser(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                    })

                    swalWithBootstrapButtons.fire({
                    title: '¿Está seguro que desea desactivar usuario?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('/user/disable', {
                            'id': id
                        }).then(function (response) {
                            me.listPeople(1,'','name');
                            swalWithBootstrapButtons.fire(
                            'Desactivado!',
                            'El registro ha sido descativado con exito.',
                            'success'
                            )
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                        
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {}
                })
            },
            ableUser(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                    })

                    swalWithBootstrapButtons.fire({
                    title: '¿Está seguro que desea activar usuario?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('/user/able', {
                            'id': id
                        }).then(function (response) {
                            me.listPeople(1,'','name');
                            swalWithBootstrapButtons.fire(
                            'Activado!',
                            'El registro ha sido activado con exito.',
                            'success'
                            )
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                        
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {}
                })
            },
        },
        mounted() {
            this.listPeople(1, this.search, this.criteria);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .show{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>