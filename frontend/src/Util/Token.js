export const saveToken = (token) => {
    if (token) {
        localStorage.setItem("ACCESS_TOKEN", token);
      } else {
        localStorage.removeItem("ACCESS_TOKEN");
      }
}

export const getToken = () => {
    return localStorage.getItem('ACCESS_TOKEN');
}