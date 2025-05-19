<template>
  <div v-show="visible" class="modal-overlay" @click.self="close">
    <div class="modal-content">
      <section class="container-fluid">
        <div class="row">
          <div class="col-12 d-flex align-items-center justify-content-center">
            <div class="webcam h-100">
              <video v-show="webcamDisplayClient" ref="video" autoplay></video>
              <canvas width="400" height="300" v-show="canvasDisplayClient" ref="clientCanvas"></canvas>
              <input class="form-control form-control-lg mt-2" id="formFileLg" type="file"
                @change="(e) => this.clientImageUpload(e)" ref="clientImageUpload">
              <button v-if="this.streamClient"
                class="btn bg-secondary text-white w-100 d-flex justify-content-center align-items-center mt-2"
                style="font-size: 20px;" @click="this.captureImageClient()">Capture</button>
              <button v-if="this.streamClient === null || this.streamClient == ''"
                class="btn bg-secondary text-white w-100 d-flex justify-content-center align-items-center mt-2"
                style="font-size: 20px;" @click="this.startCameraClient()">{{ this.startWebOrRecaptureClient
                }}</button>
              <button v-if="this.webcamDisplayClient && this.streamClient != null"
                class="btn bg-secondary text-white w-100 d-flex justify-content-center align-items-center mt-2"
                :style="{ 'font-size': '20px' }" @click="this.stopWebcamClient()">Stop Webcam</button>
                <button v-if="this.computedClientImage !== ''"
                class="btn bg-secondary text-white w-100 d-flex justify-content-center align-items-center mt-2"
                :style="{ 'font-size': '20px' }" @click="this.stopWebcamClient()">No Image</button>
            </div>
          </div>
        </div>
      </section>
      <button class="close-btn" @click="close">&times;</button>

    </div>
  </div>
</template>

<script>
export default {
  name: 'ImageModal',
  mounted() {

    if(this.computedClientImage == '' || this.computedClientImage == null){
      this.stopWebcamClient();
    }else{
      
    
      const img = new Image();
      
      const canvas = this.$refs.clientCanvas;
      const ctx = canvas.getContext('2d');
     
      // Set canvas dimensions to match video dimensions
  

      // Draw the current video frame onto the canvas
      //can be url or base64 auto convert
      img.src = this.computedClientImage;
    
      img.onload = function () {
        canvas.width = img.width;
        canvas.height = img.width;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
      
      }
    }
  },
  beforeUnmount(){
    document.body.style.overflow = 'scroll';
  },
  created() {

  },
  data() {
    return {
      streamClient: "",
      startWebOrRecaptureClient: "Start Webcam",
      webcamDisplayClient: false,
      canvasDisplayClient: true,
    }
  },
  props: {
    visible: {
      type: Boolean,
      required: true
    }
  },
  computed:{
    computedClientImage:{
      get(){
        return this.$store.state.client.client_image;
      },
      set(value){
        this.$store.commit('setClientImage',{client_image:value});
      }
    }
  },
  methods: {
    clientImageUpload(event) {
      // Ensure the event is from an input element
      if (event.target && event.target.files) {

        const file = event.target.files[0];
        const canvas = this.$refs.clientCanvas;
        const ctx = canvas.getContext('2d');

        if (file) {
          const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/bmp', 'image/webp'];
          if (validImageTypes.includes(file.type)) {
            if (this.streamClient) {
              // Stop all tracks
              this.streamClient.getTracks().forEach(track => track.stop());
              this.streamClient = null; // Clear the reference
              this.startWebOrRecaptureClient = "Start Webcam";
              this.webcamDisplayClient = false;
              this.canvasDisplayClient = true;
            }
            this.startWebOrRecaptureClient = 'Start Webcam';

            const reader = new FileReader();

            reader.readAsDataURL(file);

            reader.onload = (e) => {
              const img = new Image();
              img.src = e.target.result;

              this.computedClientImage = img.src;
           

              img.onload = () => {
                // Set canvas size to the image size
                canvas.width = img.width;
                canvas.height = img.height;

                // Draw the image on the canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
              };
            };


            

          } else {
            // Handle invalid image type if necessary
          }
        } else {
          this.$refs.clientImageUpload.value = '';
        }
      } else {
        // Handle the case where event.target or event.target.files is undefined
        console.error('Invalid event target or files not found');
      }
    },
    async startCameraClient() {
      try {
        this.webcamDisplayClient = true;
        this.canvasDisplayClient = false;
        this.streamClient = await navigator.mediaDevices.getUserMedia({ video: true });
        
        const video = this.$refs.video;
        video.srcObject = this.streamClient;
        this.startWebOrRecaptureClient = 'Recapture';

        this.computedClientImage = '';
        this.$refs.clientImageUpload.value = '';

      } catch (err) {
        // this.stream.getTracks().forEach(track => track.stop());
        this.streamClient = null; // Clear the reference
        this.startWebOrRecaptureClient = "Start Webcam";
        this.webcamDisplayClient = false;
        this.canvasDisplayClient = true;

        this.computedClientImage = '';
        this.$refs.clientImageUpload.value = '';
        console.error("Error accessing webcam:", err);


      }
    },
    async captureImageClient() {

      // Get the video and canvas elements
      const video = this.$refs.video;
      const canvas = this.$refs.clientCanvas;
      const ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      // Set canvas dimensions to match video dimensions
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;

      // Draw the current video frame onto the canvas

      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Convert canvas to image data URL and store it in data property
      this.computedClientImage = canvas.toDataURL('image/png');
      this.webcamDisplayClient = false;
      this.canvasDisplayClient = true;

      this.$refs.clientImageUpload.value = '';
      

      if (this.streamClient) {
        // Stop all tracks
        this.streamClient.getTracks().forEach(track => track.stop());
        this.streamClient = null; // Clear the reference
      }

    },
    stopWebcamClient() {
      if (this.streamClient) {
        // Stop all tracks
        this.streamClient.getTracks().forEach(track => track.stop());
        this.streamClient = null; 
        this.startWebOrRecaptureClient = "Start Webcam";
        this.webcamDisplayClient = false;
        this.canvasDisplayClient = true;

      }
      const canvas = this.$refs.clientCanvas;
      const ctx = canvas.getContext('2d');

      const img = new Image();
      img.src = "/storage/images/cityofgeneraltrias.webp";

      ctx.clearRect(0, 0, canvas.width, canvas.height);

      img.onload = function () {
        var hRatio = canvas.width / img.width;
        var vRatio = canvas.height / img.height;
        var ratio = Math.min(0.5, 0.5);
        var centerShift_x = (canvas.width - (img.width * ratio)) / 2;
        var centerShift_y = (canvas.height - (img.height * ratio)) / 2;

     
        ctx.drawImage(img, centerShift_x, centerShift_y, img.width * ratio, img.height * ratio);
      };

      
      this.computedClientImage = '';
      this.$refs.clientImageUpload.value = '';
    },
    close() {
      this.$emit('update:visible', false);

      if (this.streamClient) {
          // Stop all tracks
          this.streamClient.getTracks().forEach(track => track.stop());
          this.streamClient = null; // Clear the reference
          this.startWebOrRecaptureClient = "Start Webcam";
          this.webcamDisplayClient = false;
          this.canvasDisplayClient = true;
  
  
          this.$refs.clientImageUpload.value = '';
        }
    }
  }
}
</script>

<style scoped>
video,
canvas {
  height: 400px;
  /* Set the desired height */
  width: 100%;
  /* Width adjusts automatically */
  object-fit: cover;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);

}


.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  position: relative;
  max-width: 500px;
  width: 100%;
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  border: none;
  background: none;
  font-size: 24px;
  cursor: pointer;
}
</style>