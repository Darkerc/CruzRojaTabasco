<template>
<div></div>
</template>

<script>
export default {
    head: {
        title: "Cursos"
    },
    async asyncData(contex) {
        const {
            title
        } = await new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    title: "Tera 1"
                })
            }, 3000);
        });

        return {
            title
        }
    }
}
</script>
