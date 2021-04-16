<template>
  <span style="float: right">
    <em class="fa fa-caret-up" v-show="sortColumn == columnName && sortDir == 'asc'" />
    <em class="fa fa-caret-down" v-show="sortColumn == columnName && sortDir == 'desc'" />
  </span>
</template>

<script>
  export default {
    props: ['columnName', 'sortColumn', 'sortDir'],
  }
</script>