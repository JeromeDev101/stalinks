<template>
    <div v-if="!closed" class="card" :class="classes">
        <div class="card-header p-2 bln-header">
            <div class="bln-title float-left">
                <h6 class="p-0 mb-0" :title="title">{{ title }}</h6>
            </div>

            <div class="bln-buttons float-right">
                <i :class="concise ? 'fa fa-chevron-up' : 'fa fa-chevron-down'" @click="toggleConcise"></i>
                <i class="bln-expand" :class="maximized ? 'fa fa-compress' : 'fa fa-expand'" @click="toggleMaximize"></i>
                <i v-if="showCloseButton" class="fa fa-times" @click="close"></i>

                <slot name="header-buttons"></slot>
            </div>
        </div>

        <div class="card-body bln-content p-3">
            <slot>

            </slot>
        </div>
    </div>
</template>

<script>

export default {
    name: 'Balloon',
    props: {
        title: {
            default: '',
            type: String
        },

        // position: bottom-right, bottom-left, top-right, or top-left
        position: {
            default: 'bottom-right',
            type: String
        },

        showCloseButton: {
            default: false,
            type: Boolean
        },

        // when balloon is created it will have this initial 'concise' state
        initiallyConcise: {
            default: false,
            type: Boolean
        },

        // keep track of balloon state
        balloonState: {
            default () {
                return {
                    maximized: false
                }
            },
            type: Object
        },

        // force the minimized state
        forceMinimized: {
            default: true,
            type: Boolean
        }
    },

    data() {
        return {
            closed: true,
            concise: this.initiallyConcise,
        }
    },

    computed: {
        classes () {
            return [
                'bln',
                this.concise ? 'bln-concise' : '',
                this.maximized && !this.concise ? 'bln-maximized' : 'bln-minimized',
                this.position ? `bln-${this.position}` : '',
            ]
        },

        maximized () {
            return this.balloonState.maximized === this.forceMinimized
        }
    },

    methods: {
        close () {
            this.closed = true;
            this.resetState();
            this.$emit('close', this);
        },

        open () {
            this.closed = false;
            this.$emit('open', this);
        },

        resetState () {
            this.concise = false;
            this.balloonState.maximized = false;
        },

        toggleMaximize () {
            this.balloonState.maximized = !this.balloonState.maximized;
            this.concise = false;
        },

        toggleConcise () {
            this.concise = !this.concise;
        },
    },
}
</script>

<style scoped>

.bln {
    pointer-events: all !important;
    margin-left: 5px;
}

.bln-wrapper {
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1040;
    position: fixed;
    overflow: hidden;
}

.bln-minimized {
    z-index: 10000;
    position: fixed;
}

.bln-maximized {
    width: 75%;
    z-index: 10000;
    box-shadow: 0 0 0;
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    margin: 1.75rem auto;
}

.bln-minimized.bln-bottom-right {
    right: 0;
    bottom: 0;
    position: relative;
}

.bln-minimized.bln-bottom-left {
    left: 0;
    bottom: 0;
    position: fixed;
 }

.bln-minimized.bln-top-left {
    top: 0;
    left: 0;
    position: fixed;
 }

.bln-minimized.bln-top-right {
    top: 0;
    right: 0;
    position: fixed;
 }

.bln-header {
    padding: 5px;
    color: white;
    background-color: #3c8dbc;
}

.bln-header .bln-title {
    max-width: 350px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.bln-header .bln-title h6 {
    max-width: 350px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.bln-buttons i {
    transition: opacity 0.25s;
    cursor: pointer;
    font-size: 15px;
    margin-left: 5px;
}

.bln-buttons i:hover {
    opacity: 0.5;
}

.bln-maximized .bln-content {
    height: auto;
    min-height: 10px;
    width: 100%;
    overflow: hidden;
    position: relative;
    /*background: #eceae8;*/
}

.bln-minimized .bln-content {
    position: relative;
    width: 650px;
    height: 700px;
    overflow-y: scroll;
}

.bln-concise .bln-content {
    height: 0;
    width: 265px;
    max-height: 0;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

.bln-concise .bln-title {
    max-width: 150px;
}

.bln-concise .bln-content .card-body{
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

/*------------------------------------------
  Responsive Grid Media Queries - 1280, 1024, 768, 480
   1280-1024   - desktop (default grid)
   1024-768    - tablet landscape
   768-480     - tablet
   480-less    - phone landscape & smaller
--------------------------------------------*/
@media all and (min-width: 1024px) and (max-width: 1280px) {

    .bln-maximized {
        width: 75%;
    }

}

@media all and (min-width: 768px) and (max-width: 1024px) {

    .bln-maximized {
        width: 75%;
    }

}

@media all and (min-width: 480px) and (max-width: 768px) {

    .bln-expand {
        display: none;
    }

    .bln-minimized .bln-content {
        width: 100%;
        height: 80%;
    }

    .bln-concise.bln-minimized {
        width: 265px;
        margin: 1rem;
    }

    .bln-maximized {
        width: 95%;
    }

    .bln-minimized {
        width: 95%;
        margin: 1.75rem auto;
    }

    .bln-minimized.bln-bottom-right {
        position: relative;
    }

    .bln-minimized.bln-bottom-left {
        position: relative;
    }

    .bln-minimized.bln-top-left {
        position: relative;
    }

    .bln-minimized.bln-top-right {
        position: relative;
    }

    .bln-concise.bln-minimized.bln-bottom-right {
        position: fixed;
    }

    .bln-concise.bln-minimized.bln-bottom-left {
        position: fixed;
    }

    .bln-concise.bln-minimized.bln-top-left {
        position: fixed;
    }

    .bln-concise.bln-minimized.bln-top-right {
        position: fixed;
    }

}

@media all and (max-width: 480px) {

    .bln-expand {
        display: none;
    }

    .bln-minimized .bln-content {
        width: 100%;
        height: 80%;
    }

    .bln-concise.bln-minimized {
        width: 265px;
        margin: 1rem;
    }

    .bln-maximized {
        width: 95%;
    }

    .bln-minimized {
        width: 95%;
        margin: 1.75rem auto;
    }

    .bln-minimized.bln-bottom-right {
        position: relative;
    }

    .bln-minimized.bln-bottom-left {
        position: relative;
    }

    .bln-minimized.bln-top-left {
        position: relative;
    }

    .bln-minimized.bln-top-right {
        position: relative;
    }

    .bln-concise.bln-minimized.bln-bottom-right {
        position: fixed;
    }

    .bln-concise.bln-minimized.bln-bottom-left {
        position: fixed;
    }

    .bln-concise.bln-minimized.bln-top-left {
        position: fixed;
    }

    .bln-concise.bln-minimized.bln-top-right {
        position: fixed;
    }

}

</style>
