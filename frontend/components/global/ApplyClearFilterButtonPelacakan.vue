<template>
  <div>
    <button class="btn btn-primary" @click="actionDoFilter" style="margin-right: 10px">
      <em class="fa fa-search" /> Search
    </button>
    <button class="btn btn-clear" @click="actionClearFilter" style="margin-right: 10px">
      <em class="fa fa-close" /> Clear Search
    </button>
  </div>
</template>

<script>
  export default {
    name: 'ApplyClearFilterButtonPelacakan',
    props: {
      doFilter: Function,
      clearFilter: Function
    },
    data: function () {
      return {
        actionDoFilter: this.doFilter,
        actionClearFilter: this.clearFilter
      }
    }
  }
</script>