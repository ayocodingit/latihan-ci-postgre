export const state = () => ({
  oid: '',
})

export const mutations = {
  add(state, data) {
    state.oid = data
  },
  remove(state) {
    state.oid = ''
  },
}
