import axios from "axios";

const api = axios.create({
  baseURL: 'http://api-dev-ff.byworks.com.br'
});

export default api;