import axios from 'axios';

const checkEmailExists = async (email: string) => {
    try {
        const checkEmailResponse = await axios.post<{ exists: boolean }>(route('checkEmail'), { email });
        return checkEmailResponse.data.exists;
    } catch (error) {
        console.error(error);
        return false;
    }
};

export { checkEmailExists };
