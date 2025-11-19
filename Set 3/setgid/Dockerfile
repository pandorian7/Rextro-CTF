FROM alpine:latest

ARG user=tokyo
ARG pass=rio

ARG adminUser=prof

ARG FlagFile=artifact

ARG ExecName=masterkey

RUN apk add --no-cache openssh gcc musl-dev libcap

RUN adduser -D -h /home/${user} ${user} && echo "${user}:${pass}" | chpasswd
RUN adduser -D -s /sbin/nologin ${adminUser}


COPY proc.c /home/${user}/proc.c
COPY proc_root.c /home/${user}/proc_root.c
COPY flag.txt /home/${user}/${FlagFile}
COPY notice.txt /etc/motd

RUN gcc /home/${user}/proc_root.c -o /home/${user}/proc
RUN rm /home/${user}/proc_root.c
RUN chown ${adminUser}:${adminUser} /home/${user}/proc /home/${user}/${FlagFile} /home/${user}/proc.c
RUN mv /home/${user}/proc /usr/bin/${ExecName}
RUN chmod 400 /home/${user}/${FlagFile}
RUN chmod 444 /home/${user}/proc.c
RUN chmod 4111 /usr/bin/${ExecName}

RUN setcap cap_setuid=eip /usr/bin/${ExecName}

RUN echo "mv proc /usr/bin/${ExecName}" > /home/${user}/.ash_history
RUN echo "alias ls='ls --color=never'" >> /etc/profile

RUN    echo "PasswordAuthentication yes" >> /etc/ssh/sshd_config

RUN ssh-keygen -A

EXPOSE 22

CMD ["/usr/sbin/sshd", "-D"]
# CMD ["/bin/sh"]
