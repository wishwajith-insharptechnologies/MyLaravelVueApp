import Http from '@/services/Http.js';

export const getSession = async (productId) => {
    try {
        const response = await Http.post(`payment/get-session`, {
            productId: productId,
          });
        return response;
    } catch (error) {
        throw error;
    }
};

export const completePayment = async (paymentToken, paymentIntentId ) => {
    try {
        const response = await Http.post(`payment/complete`, {
            payment_token: paymentToken,
            payment_Intent_Id: paymentIntentId,
          });
        return response.data;
    } catch (error) {
        throw error;
    }
};
