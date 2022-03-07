<template>
    <div class="row">
        <div v-for="siteData in sites" class="col-lg-4 col-md-6">
            <div class="card analysis-card">
                <div class="analysis-web-img image-holder">
                    <img :src="siteData.demoDetails ? siteData.demoDetails.theme_image: ''"
                         class="card-img-top" :alt="siteData.title">
                    <a class="btn btn-icon btn-dark text-white" target="_blank"
                       :href="createUrl(siteData.server_ip)" role="button">
                        <i class="fe fe-eye"></i>
                    </a>
                </div>
                <div class="card-body p-3">
                    <a target="_blank" class="theme-title-text"
                       :href="createUrl(siteData.server_ip)">
                        {{ siteData.title }}
                    </a>
                    <div v-if="siteData.score" class="analysis-website">
                        <div class="aw-list">
                            Score
                            <span>
                                <b class="count"> {{ siteData.score }}</b>/100
                            </span>
                        </div>
                        <div class="aw-list">
                            Global Rank <span>{{ siteData.global_rank }}</span>
                        </div>
                        <div class="aw-list">
                            Page Speed <span>{{ siteData.page_speed }}%</span>
                        </div>
                    </div>
                    <div v-if="!siteData.score" class="analysis-website bg-white">
                        <button v-if="siteData.score_process" disabled class="btn btn-primary btn-block"
                                style="cursor: not-allowed">Processing {{ checkScoreProcess() }}
                        </button>
                        <button v-if="!siteData.score_process" class="btn btn-primary btn-block" @click="generateScore(siteData)">
                            Click here to generate Score, Rank & Speed
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from "axios";

export default {
    props: {
        sites_data: {
            required: true
        }
    },
    data() {
        return {
            sites: JSON.parse(this.sites_data),
        }
    },
    methods: {
        generateScore(siteData) {
            siteData.score_process = true;
            axios.get(`/generate-score?server_ip=${siteData.server_ip}`)
                .then(response => {
                    this.checkScoreProcess();
                })
        },
        checkScoreProcess() {
            setTimeout(() => {
                axios.get('/check-score-process')
                    .then(response => {
                        console.log(response.data)
                        this.sites = response.data;
                    })
            }, 60000)
        },
        createUrl(url) {
            return '/score-details?url=' + btoa(`https://analysis.codibu.com/domain/${url}`)
        }
    }
}
</script>
