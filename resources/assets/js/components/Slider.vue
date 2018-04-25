<template>
<div class="slider">
    <!-- Slideshow container -->
    <div class="slide-container">
        <!-- Full-width images with number and caption text -->
        <div class="slide" v-for="(image, index) in items"  v-show="isCurrent(index)">
            <img :src="image">
        </div>

        <!-- Next and previous buttons -->
        <a class="prev" @click="goToPrevSlide" v-if="moreThanOne">&#10094;</a>
        <a class="next" @click="goToNextSlide" v-if="moreThanOne">&#10095;</a>
    </div>

    <!-- The dots/circles -->
    <div class="dots" v-if="moreThanOne">
        <div
            v-for="(image, index) in items"
            class="dot"
            :class="{'active': isCurrent(index)}"
            @click="setCurrentSlide(index)"
        ></div>
    </div>
</div>
</template>

<script>
export default {
    name: 'nzSlider',

    props: {
        items: {
            type: Array,
            default: () => []
        }
    },

    data() {
        return {
            item: 0,
        };
    },

    computed: {
        moreThanOne() {
            return this.items.length > 1;
        }
    },

    methods: {
        goToPrevSlide() {
            if (this.item > 0) {
                this.item--;
            } else {
                this.item = this.items.length - 1;
            }
        },

        goToNextSlide() {
            if (this.item < this.items.length - 1) {
                this.item++;
            } else {
                this.item = 0
            }
        },

        setCurrentSlide(index) {
            this.item = index;
        },

        isCurrent(index) {
            return this.item === index;
        }
    },
}
</script>
