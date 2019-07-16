import $axios from '../api.js'

const state = () => ({
    couriers: [], //UNTUK MENAMPUNG DATA KURIR
    page: 1, //PAGE AKTIF
    id: '' //NANTI AKAN DIGUNAKAN UNTUK EDIT DATA
})

const mutations = {
    //MEMASUKKAN DATA YANG DITERIMA KE DALAM STATE KURIR
    ASSIGN_DATA(state, payload) {
        state.couriers = payload
    },
    //MENGUBAH STATE PAGE
    SET_PAGE(state, payload) {
        state.page = payload
    },
    //MENGUBAH STATE ID
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    }
}

const actions = {
    //FUNGSI INI AKAN MELAKUKAN REQUEST KE SERVER UNTUK MENGAMBILD ATA
    getCouriers({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload : ''
        return new Promise((resolve, reject) => {
            //DENGAN MENGGUNAKAN AXIOS METHOD GET
            $axios.get(`/couriers?page=${state.page}&q=${search}`)
                .then((response) => {
                    //KEMUDIAN DI COMMIT UNTUK MELAKUKAN PERUBAHA STATE
                    commit('ASSIGN_DATA', response.data)
                    resolve(response.data)
                })
        })
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}