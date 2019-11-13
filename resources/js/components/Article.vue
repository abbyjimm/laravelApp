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
                    <i class="fa fa-align-justify"></i> Aticulos
                    <button type="button" @click="openModal('article', 'insert')" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                    <option value="name">Nombre</option>
                                    <option value="description">Descripción</option>
                                </select>
                                <input type="text" v-model="search" @keyup.enter="listArticle(1, search, criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listArticle(1, search, criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categorias</th>
                                <th>Precio de Venta</th>
                                <th>Stock</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="article in arrayArticle" :key="article.id">
                                <td>
                                    <button type="button" @click="openModal('article', 'update', article)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="article.condition">
                                        <button type="button" class="btn btn-danger btn-sm" @click="disableArticle(article.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-info btn-sm" @click="ableArticle(article.id)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="article.code"></td>
                                <td v-text="article.name"></td>
                                <td v-text="article.name_category"></td>
                                <td v-text="article.sale_price"></td>
                                <td v-text="article.stock"></td>
                                <td v-text="article.description"></td>
                                <td>
                                    <div v-if="article.condition">
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
                                <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                <div class="col-md-9"> 
                                    <select class="form-control" v-model="idcategory">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="category in arrayCategory" :key="category.id" :value="category.id" v-text="category.name"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="code" class="form-control" placeholder="Código de barras">
                                    <barcode :value="code" :options="{format:'EAN-13'}">
                                        Generando código de barras.
                                    </barcode>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="name" class="form-control" placeholder="Nombre de categoría">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Precio de Venta</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="sale_price" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="stock" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="description" class="form-control" placeholder="Ingrese descripción">
                                </div>
                            </div>
                            <div v-show="errorArticle" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorShowMsgArticle" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="insertArticle()">Guardar</button>
                        <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updateArticle()">Actualizar</button>
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
import VueBarcode from 'vue-barcode';
    export default {
        data(){
            return{
                article_id : 0,
                idcategory: 0,
                name_category:'',
                code:'',
                name : '',
                sale_price:'',
                stock:0,
                description: '',
                arrayArticle: [],
                modal: 0,
                titleModal: '',
                typeAction: 0,
                errorArticle: 0,
                errorShowMsgArticle : [],
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
                search : '',
                arrayCategory:[]
            }
        },
        components: {
            'barcode': VueBarcode
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
            listArticle(page, search, criteria){
                let me = this;
                var url = '/article?page=' + page + '&search=' + search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    var resp = response.data;
                    me.arrayArticle= resp.articles.data;
                    me.pagination = resp.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCategory(){
                let me = this;
                var url = '/category/selectCategory';
                axios.get(url).then(function(response){
                    var resp = response.data;
                    me.arrayCategory = resp.categories;
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            changePage(page, search, criteria){
                let me = this;
                //update to current page
                me.pagination.current_page = page;
                //send the request to view the pages's data
                me.listArticle(page, search, criteria);
            },
            insertArticle(){
                if(this.validateArticle()){
                    return;
                }

                let me=this;
                axios.post('/article/insert', {
                    'idcategory': this.idcategory,
                    'code': this.code,
                    'name': this.name,
                    'stock': this.stock,
                    'sale_price': this.sale_price,
                    'description': this.description
                }).then(function (response) {
                    me.closeModal();
                    me.listArticle(1,'','name');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            updateArticle(){
                if(this.validateArticle()){
                    return;
                }

                let me=this;
                axios.put('/article/update', {
                    'id': this.article_id,
                    'idcategory': this.idcategory,
                    'code': this.code,
                    'name': this.name,
                    'stock': this.stock,
                    'sale_price': this.sale_price,
                    'description': this.description                    
                }).then(function (response) {
                    me.closeModal();
                    me.listArticle(1,'','name');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            disableArticle(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                    })

                    swalWithBootstrapButtons.fire({
                    title: '¿Está seguro que desea desactivar el artículo?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('/article/disable', {
                            'id': id
                        }).then(function (response) {
                            me.listArticle(1,'','name');
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
            ableArticle(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                    })

                    swalWithBootstrapButtons.fire({
                    title: '¿Está seguro que desea activar el artículo?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('/article/able', {
                            'id': id
                        }).then(function (response) {
                            me.listArticle(1,'','name');
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
            validateArticle(){
                this.errorArticle = 0;
                this.errorShowMsgArticle = [];

                if(this.idcategory == 0) this.errorShowMsgArticle.push("Seleccione una categoria.");
                if(!this.name) this.errorShowMsgArticle.push("El nombre del artículo no puede estar vacio.");
                if(!this.stock) this.errorShowMsgArticle.push("El stock debe ser un número y no puede estar vacio.");
                if(!this.sale_price) this.errorShowMsgArticle.push("El precio de venta del artículo debe ser un número y no puede estar vacio.");
                
                if(this.errorShowMsgArticle.length) this.errorArticle = 1;

                return this.errorArticle;            
            },
            closeModal(){
                this.modal = 0;
                this.titleModal = '';
                this.idcategory = 0;
                this.name_category = '';
                this.code = '';
                this.name = '';
                this.sale_price = 0,
                this.stock = 0;
                this.description = '';
                this.errorArticle = 0;
            },
            openModal(model, action, data = []){                
                switch (model) {
                    case 'article':
                    {
                        switch(action){
                            case 'insert':
                            {
                                this.modal = 1;
                                this.titleModal = 'Registrar Artículo';
                                this.idcategory = 0;
                                this.name_category = '';
                                this.code = '';
                                this.name = '';
                                this.sale_price = 0,
                                this.stock = 0;
                                this.description = '';                                
                                this.typeAction = 1;
                                break;
                            }
                            case 'update':
                            {
                                this.modal = 1;
                                this.titleModal = 'Actualizar Artículo';
                                this.typeAction = 2;  
                                this.article_id = data['id'];  
                                this.idcategory = data['idcategory'];
                                this.code = data['code'];
                                this.name = data['name'];
                                this.sale_price = data['sale_price'];
                                this.stock = data['stock'];
                                this.description = data['description'];                            
                                break;
                            }
                        }
                    }
                }
                this.selectCategory();
            }
        },
        mounted() {
            this.listArticle(1, this.search, this.criteria);
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