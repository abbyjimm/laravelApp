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
                    <i class="fa fa-align-justify"></i> Ingresos
                    <button type="button" @click="showDetail()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <!-- List -->
                <template v-if="list">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                    <option value="type_voucher">Tipo de comprobante</option>
                                    <option value="num_voucher">Numero de comprobante</option>
                                    <option value="date_time">Fecha-Hora</option>
                                </select>
                                <input type="text" v-model="search" @keyup.enter="listIncome(1, search, criteria)" class="form-control" placeholder="Texto a buscar">
                                <button type="submit" @click="listIncome(1, search, criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Usuario</th>
                                <th>Proveedor</th>
                                <th>Tipo Cmprobante</th>
                                <th>Serie Comprobante</th>
                                <th>Número Comporbante</th>
                                <th>Fecha Hora</th>
                                <th>Total</th>
                                <th>Impuesto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="income in arrayIncome" :key="income.id">
                                <td>
                                    <button type="button" @click="openModal('income', 'update', income)" class="btn btn-warning btn-sm">
                                        <i class="icon-eye"></i>
                                    </button> &nbsp;
                                    <template v-if="income.status=='Registrado'">
                                        <button type="button" class="btn btn-danger btn-sm" @click="disableIncome(income.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="income.user"></td>
                                <td v-text="income.name"></td>
                                <td v-text="income.type_voucher"></td>   
                                <td v-text="income.series_voucher"></td>  
                                <td v-text="income.num_voucher"></td>     
                                <td v-text="income.date_time"></td>      
                                <td v-text="income.total"></td>      
                                <td v-text="income.tax"></td> 
                                <td v-text="income.user"></td>    
                                <td v-text="income.status"></td>                          
                            </tr>                            
                        </tbody>
                    </table>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">

                        </table>
                    </div>
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
                </template>
                <!-- End List -->
                <!-- Detail -->
                <template v-else>
                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="">Proveedor(*)</label>
                                <v-select :on-search="selectSupplier"
                                        label="name"
                                        :options="arraySupplier"
                                        placeholder="Buscar Proveedores"
                                        :onChange="getDataSupplier"
                                >
                                    
                                </v-select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Impuesto(*)</label>
                            <input type="text" class="form-control" v-model="tax">
                        </div>
                        <div class="col md-4">
                            <div class="form-group">
                                <label>Tipo de comprobante(*)</label>
                                <select class="form-control" v-model="type_voucher">
                                    <option value="0">Seleccione</option>
                                    <option value="BOLETA">Boleta</option>
                                    <option value="FACTURA">Factura</option>
                                    <option value="TICKET">Ticket</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Serie Comprobante</label>
                                <input type="text" class="form-control" v-model="series_voucher" placeholder="000x">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Numero Comprobante(*)</label>
                                <input type="text" class="form-control" v-model="num_voucher" placeholder="000x">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div v-show="errorIncome" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorShowMsgIncome" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="col-md-6">
                            <label>Artículo <span style="color:red;" v-show="idArticle==0">(*Seleccione)</span></label>
                            <div class="form-inline">
                                <input type="text" class="form-control" v-model="code" @keyup.enter="searchArticle()" placeholder="Ingrese articulo">
                                <button @click="openModal()" class="btn btn-primary">...</button>
                                <input type="text" readonly class="form-control" v-model="article">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Precio<span style="color:red;" v-show="price==0">(*Ingrese)</span></label>
                                <input type="number" value="0" class="form-control" v-model="price">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cantidad<span style="color:red;" v-show="quantity==0">(*Ingrese)</span></label>
                                <input type="number" value="0" class="form-control" v-model="quantity">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button @click="addDetail()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Artículo</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetail.length">
                                    <tr v-for="(detail,index) in arrayDetail" :key="detail.id">
                                        <th>
                                            <button @click="deleteDetail(index)" class="btn btn-danger btn-sm">
                                                <i class="icon-close"></i>
                                            </button>
                                        </th>
                                        <th v-text="detail.article">
                                        </th>
                                        <th>
                                            <input v-model="detail.price" type="text" value="3" class="form-control">
                                        </th>
                                        <th>
                                            <input v-model="detail.quantity" type="text" value="2" class="form-control">
                                        </th>
                                        <th>
                                            {{detail.price * detail.quantity}}
                                        </th>
                                    </tr>
                                    <tr style="background-color: #CEECF5;">
                                        <td colspan="4" aling="right"><strong>Total parcial</strong></td>
                                        <td>$ {{subtotal = (total-totalTax).toFixed(2)}}</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5;">
                                        <td colspan="4" aling="right"><strong>Total impuesto</strong></td>
                                        <td>$ {{totalTax = ((total*tax)/(1+tax)).toFixed(2)}}</td>
                                    </tr>
                                     <tr style="background-color: #CEECF5;">
                                        <td colspan="4" aling="right"><strong>Total Neto</strong></td>
                                        <td>$ {{total=calculateTotal}}</td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="5">
                                            No hay artículos agregados
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" @click="hideDetail()" class="btn btn-secundary">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="registerIncome()">Registrar Ingreso</button>
                        </div>
                    </div>
                </div>
                </template>
                <!-- End Detail -->
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
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteriaA">
                                        <option value="name">Nombre</option>
                                        <option value="description">Descripción</option>
                                        <option value="code">Código</option>
                                    </select>
                                    <input type="text" v-model="searchA" @keyup.enter="listArticle(searchA, criteriaA)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listArticle(searchA, criteriaA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categorias</th>
                                        <th>Precio de Venta</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="article in arrayArticle" :key="article.id">
                                        <td>
                                            <button type="button" @click="addDetailModal(article)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="article.code"></td>
                                        <td v-text="article.name"></td>
                                        <td v-text="article.name_category"></td>
                                        <td v-text="article.sale_price"></td>
                                        <td v-text="article.stock"></td>
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
                        </div>
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
    import vSelect from 'vue-select'
    export default {
        data(){
            return{
                income_id : 0,
                idsupplier : 0,
                type_voucher: 'BOLETA',
                series_voucher: '',
                num_voucher: '',
                tax: 0.18,
                total: 0.0,
                totalTax: 0.0,
                subtotal: 0.0,
                arrayIncome: [],
                arraySupplier: [],
                arrayDetail: [],
                list: 1,
                modal: 0,
                titleModal: '',
                typeAction: 0,
                errorIncome: 0,
                errorShowMsgIncome : [],
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0 
                },
                offset : 3,
                criteria : 'num_voucher',
                search : '',
                criteriaA : 'name',
                searchA : '',
                arrayArticle: [],
                idArticle: 0,
                article: '',
                price: 0,
                code: '',
                quantity: 0
            }
        },
        components: {
            vSelect
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
            },
            calculateTotal: function(){
                var result = 0.0;

                for(var i=0; i<this.arrayDetail.length; i++){
                    result = result+(this.arrayDetail[i].price * this.arrayDetail[i].quantity);
                }
                return result;
            }
        },
        methods :{
            listIncome(page, search, criteria){
                let me = this;
                var url = '/income?page=' + page + '&search=' + search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    var resp = response.data;
                    me.arrayIncome= resp.income.data;
                    me.pagination = resp.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectSupplier(search, loading){
                let me = this;
                loading(true)
                var url = '/supplier/selectSupplier?filter='+search;
                axios.get(url).then(function (response) {
                    let resp = response.data;
                    q: search;
                    me.arraySupplier = resp.supplier;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDataSupplier(val1){
                let me = this;
                me.loading = true;
                me.idsupplier = val1.id;
            },
            searchArticle(){
                let me = this;
                var url = '/article/searchArticle?filter='+ me.code;

                axios.get(url).then(function (response){
                    var resp = response.data;
                    me.arrayArticle = resp.articles;

                    if(me.arrayArticle.length > 0){
                        me.article = me.arrayArticle[0]['name'];
                        me.idArticle = me.arrayArticle[0]['id'];
                    }else{
                        me.article = 'No existe el articulo';
                        me.idArticle = 0;
                    }
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            changePage(page, search, criteria){
                let me = this;
                //update to current page
                me.pagination.current_page = page;
                //send the request to view the pages's data
                me.listIncome(page, search, criteria);
            },
            found(id){
                var sw = 0;
                for(var i=0; i<this.arrayDetail.length; i++){
                    if(this.arrayDetail[i].idArticle == id){
                        sw = true;
                    }
                }
                return sw;
            },
            deleteDetail(index){
                let me= this;
                me.arrayDetail.splice(index, 1);
            },
            addDetail(){
                let me = this;
                if(me.idArticle == 0 || me.price == 0 || me.quantity == 0){

                }else{
                    if(me.found(me.idArticle)){
                        swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'Este artículo ya se encuentra agregado'
                        })
                    }else{
                        me.arrayDetail.push({
                            idArticle: me.idArticle,
                            article: me.article,
                            price: me.price,
                            quantity: me.quantity
                        });
                        me.code = "";
                        me.idArticle = 0;
                        me.article ="";
                        me.price = 0;
                        me.quantity = 0;
                    }
                }               
            },
            addDetailModal(data = []){
                let me = this;
                if(me.found(data['id'])){
                    swal.fire({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este artículo ya se encuentra agregado'
                    })
                }else{
                    me.arrayDetail.push({
                        idArticle: data['id'],
                        article: data['name'],
                        price: 1,
                        quantity: 1
                    });
                }
            },
            listArticle(search, criteria){
                let me = this;
                var url = '/article/listArticles?search=' + search + '&criteria=' + criteria;
                axios.get(url).then(function (response) {
                    var resp = response.data;
                    me.arrayArticle= resp.articles.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registerIncome(){
                if(this.validateIncome()){
                    return;
                }

                let me=this;
                axios.post('/income/insert', {
                    'idsupplier': this.idsupplier,
                    'type_voucher': this.type_voucher,
                    'num_voucher': this.num_voucher,
                    'series_voucher': this.series_voucher,
                    'tax': this.tax,
                    'total': this.total,
                    'data' : this.arrayDetail,
                }).then(function (response) {
                    me.listIncome(1,'','num_voucher');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },      
            validateIncome(){
                this.errorIncome = 0;
                this.errorShowMsgIncome = [];

                if(this.idsupplier==0) this.errorShowMsgIncome.push('Seleccione un proveedor');
                if(this.type_voucher==0) this.errorShowMsgIncome.push('Seleccione el comprobante');         
                if(!this.num_voucher) this.errorShowMsgIncome.push('Ingrese el número de comprobante');
                if(!this.tax) this.errorShowMsgIncome.push('Ingrese el impueto de compra');
                if(this.arrayDetail.length<=0) this.errorShowMsgIncome.push('Ingrese detalles');
                
                if(this.errorShowMsgIncome.length) this.errorIncome = 1;

                return this.errorIncome;            
            },
            showDetail(){
                this.list = 0;
            },
            hideDetail(){
                this.list=1;
            },
            closeModal(){
                this.modal = 0;
                this.titleModal = '';
            },
            openModal(){
                this.arrayArticle = [];
                this.modal = 1;
                this.titleModal = 'Seleccione uno o varios artículos';
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
            this.listIncome(1, this.search, this.criteria);
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
    @media (min-width:600px){
        .btnagregar{
            margin-top: 2rem;
        }
    }
</style>