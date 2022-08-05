FROM nginx

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Manila
WORKDIR /etc/nginx

EXPOSE 80
EXPOSE 443
EXPOSE 3000
# EXPOSE 9009 # for when we need storybook

RUN echo "alias ll='ls -alF'" >> ~/.bashrc
CMD ["nginx", "-g", "daemon off;"]