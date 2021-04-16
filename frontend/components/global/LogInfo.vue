<template>
  <ul class="timeline">
    <li v-for="log in data" v-bind:key="log.id">
      <span v-if="log.created_at">
        {{`${momentFormatDate(log.created_at)} ${momentFormatTime(log.created_at)}`}}
      </span>
      <span v-if="log.updated_by">
        {{` - ${log.updated_by}`}}
      </span>
      <div v-for="(changes, index) in JSON.parse(log.info)" :key="index">
        <strong>{{ humanize(index) }}</strong>
        <ul class="pl-4" style="list-style-type: disc;">
          <li>{{ `${changes.from} > ${changes.to}` }}</li>
        </ul>
      </div>
    </li>
  </ul>
</template>

<script>
  import {
    humanize,
    momentFormatDate,
    momentFormatTime,
  } from '~/utils'

  export default {
    name: 'LogInfo',
    methods: {
      humanize,
      momentFormatDate,
      momentFormatTime,
    },
    props: {
      data: {
        type: Array,
        default: []
      },
    }
  }
</script>

<style>
  ul.timeline {
    list-style-type: none;
    position: relative;
  }

  ul.timeline:before {
    content: ' ';
    background: #d4d9df;
    display: inline-block;
    position: absolute;
    left: 29px;
    width: 2px;
    height: 100%;
    z-index: 400;
  }

  ul.timeline>li {
    margin: 20px 0;
    padding-left: 20px;
  }

  ul.timeline>li:before {
    content: ' ';
    background: white;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #22c0e8;
    left: 20px;
    width: 20px;
    height: 20px;
    z-index: 400;
  }
</style>
