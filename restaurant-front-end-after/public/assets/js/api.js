const API_URL = 'http://127.0.0.1:8000';

async function apiFetch(endpoints, options = {}){
    const token = localStorage.getItem('auth_token');//get token after login
    const header = {
        'Content-Type' : 'application/json',
        'Accept' : 'application/json',
        ...options.headers
    };

    try{
       const response = await fetch(`${API_URL}${endpoints}`,{...options,headers});
       if(!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
       return await response.json();
    }catch (e){
        console.error('API call failed:', e);
    }
}