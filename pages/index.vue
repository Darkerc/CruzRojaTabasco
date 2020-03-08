<template>
<div class="d-flex flex-column py-6">
    <img class="imgIndex" src="~/assets/img/crwelcome.webp"/>
    <h2 class="text-center display-1 my-12 mx-auto titleIndex danger">
        Bienvenido al panel de administracion
    </h2>
</div>
</template>

<script>
export default {
    transition: "slide",
    head: {
        title: "Dashboard"
    }
}
</script>

<style>
    .imgIndex{
        width: 25%;
        min-width: 300px;
        height: 25%;
        min-height: 300px;
        object-fit: cover;
        display: block;
        margin: auto;
    }

    .titleIndex{
        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        border-radius: 15px;
        box-sizing: border-box;
        padding: 20px;
        color: white;
    }
</style>
