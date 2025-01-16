<template>
	<div>
        <!-- Begin page -->
        <div id="layout-wrapper">
            
            
            <Topbar />

            <Header />

            <Sidebar />
            
            <div class="vertical-overlay"></div>

            <div class="main-content">
                
                <router-view></router-view>

                <Footer />
                
            </div>

        </div>  
        <!-- END layout-wrapper -->
        
	</div>
</template>

<script>
import Sidebar from "./Sidebar.vue";
import Footer from "./Footer.vue";
import Topbar from "./Topbar.vue";
import Header from "./Header.vue";
export default {
    name: 'Dashboard',
    components: {
        Sidebar,
        Footer,
        Topbar,
        Header,
    }
}
</script>