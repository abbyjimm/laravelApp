@extends('main')
@section('content')

    @if(Auth::check())
        @if(Auth::user()->idrole == 1)
            <template v-if="menu==0"> 
                <h1>Escritorio</h1>
            </template>

            <template v-if="menu==1"> 
                <category></category>
            </template>

            <template v-if="menu==2"> 
                <articles></articles>        
            </template>

            <template v-if="menu==3"> 
                <income></income>
            </template>

            <template v-if="menu==4"> 
                <supplier></supplier>
            </template>

            <template v-if="menu==5"> 
                <h1>Ventas</h1>
            </template>

            <template v-if="menu==6"> 
                <customer></customer>
            </template>

            <template v-if="menu==7"> 
                <user></user>
            </template>

            <template v-if="menu==8"> 
                <role></role>
            </template>

            <template v-if="menu==9"> 
                <h1>Reporte de ingresos</h1>
            </template>

            <template v-if="menu==10"> 
                <h1>Reporte de ventas</h1>
            </template>

            <template v-if="menu==11"> 
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12"> 
                <h1>Acerca de</h1>
            </template>
        @elseif(Auth::user()->idrole == 2)
            <template v-if="menu==5"> 
                <h1>Ventas</h1>
            </template>

            <template v-if="menu==6"> 
                <customer></customer>
            </template>

            <template v-if="menu==10"> 
                <h1>Reporte de ventas</h1>
            </template>

            <template v-if="menu==11"> 
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12"> 
                <h1>Acerca de</h1>
            </template>
        @elseif(Auth::user()->idrole == 3)
            <template v-if="menu==1"> 
                <category></category>
            </template>

            <template v-if="menu==2"> 
                <articles></articles>        
            </template>

            <template v-if="menu==3"> 
                <income></income>
            </template>

            <template v-if="menu==4"> 
                <supplier></supplier>
            </template>

            <template v-if="menu==9"> 
                <h1>Reporte de ingresos</h1>
            </template>

            <template v-if="menu==11"> 
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12"> 
                <h1>Acerca de</h1>
            </template>
        @else
        @endif
    @endif
@endsection