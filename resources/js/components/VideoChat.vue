<template>
  <div class="container">
    <div
      class="video-container"
      ref="video-container"
      style="padding-top: 30px"
    >
      <video class="video-here" ref="video-here" autoplay></video>
      <video class="video-there" ref="video-there" autoplay></video>
      CONNECT WITH :-
      <div class="text-left" style="display:flex; flex-direction:row;" v-for="(name, userId) in others" :key="userId">
        <button
          @click="startVideoChat(userId)"
          v-text="`${name}`"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Pusher from "pusher-js";
import Peer from "simple-peer";
export default {
  props: ["user", "others", "pusherKey", "pusherCluster","randomid"],
  data() {
    return {
      channel: null,
      stream: null,
      peers: {},
    };
  },
  mounted() {
    this.setupVideoChat();
  },
  methods: {
    startVideoChat(userId) {
      this.getPeer(userId, true);
    },
    getPeer(userId, initiator) {
      if (this.peers[userId] === undefined) {
        let peer = new Peer({
          initiator,
          stream: this.stream,
          trickle: false,
        });
        peer
          .on("signal", (data) => {
            this.channel.trigger(`client-signal-${userId}`, {
              userId: this.user.id,
              data: data,
            });
          })
          .on("stream", (stream) => {
            const videoThere = this.$refs["video-there"];
            videoThere.srcObject = stream;
          })
          .on("close", () => {
            const peer = this.peers[userId];
            if (peer !== undefined) {
              peer.destroy();
            }
            delete this.peers[userId];
          });
        this.peers[userId] = peer;
      }
      return this.peers[userId];
    },
    async setupVideoChat() {
      Pusher.logToConsole = true;
      const stream = await navigator.mediaDevices.getUserMedia({
        video: true,
        audio: true,
      });
      const videoHere = this.$refs["video-here"];
      videoHere.mute = true;
      videoHere.srcObject = stream;
      this.stream = stream;
      const pusher = this.getPusherInstance();
      this.channel = pusher.subscribe(`presence-${this.randomid}`);
      this.channel.bind(`client-signal-${this.user.id}`, (signal) => {
        const peer = this.getPeer(signal.userId, false);
        peer.signal(signal.data);
      });
    },
    getPusherInstance() {
      return new Pusher(this.pusherKey, {
        authEndpoint: "/home/join/auth",
        cluster: this.pusherCluster,
        auth: {
          headers: {
            "X-CSRF-Token": document.head.querySelector(
              'meta[name="csrf-token"]'
            ).content,
          },
        },
      });
    },
  },
};
</script>
<style>
.video-container {
  width: 100%;
  height: 520px;
  margin: 8px auto;
  border: 1px solid #1e1e1e;
  position: relative;
  box-shadow: 1px 1px 1px #9e9e9e;
}
.video-here {
  width: 200px;
  position: absolute;
  left: 10px;
  bottom: 16px;
  border: 1px solid #000;
  border-radius: 1px;
  z-index: 2;
}
.video-there {
  width: 100%;
  height: 100%;
  z-index: 1;
}
.text-right {
  text-align: left;
}
</style>