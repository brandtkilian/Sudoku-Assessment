<template>
    <div class="main-layout">
        <navbar/>

        <div class="main-container container mt-4">
            <child/>
        </div>
    </div>
</template>

<script>
import Navbar from '@/js/components/Navbar'

export default {
    name: 'MainLayout',

    components: {
        Navbar
    }
}
</script>
