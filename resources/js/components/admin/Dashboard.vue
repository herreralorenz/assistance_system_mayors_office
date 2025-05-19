<template>
  <Loader2 v-if="this.computedDashboardLoader"></Loader2>
    <div v-else class="form-group dashboard py-4">
      <div class="container-fluid">
        <div class="d-flex">
          <div class="row w-100 mx-auto">
            <div class="col-3">
              <div class="card">
                <div class="d-flex flex-row">
                  <div>
                    <span style="font-size: 14px;">{{"💸 Costing " + "("+Object.keys(this.computedDashboard.cost)[0]+")" }}</span>
                    <h4><b>{{ 'PHP '+this.computedDashboard.cost[Object.keys(this.computedDashboard.cost)[0]].toLocaleString()  }}</b></h4>
                    <span>average (2 months)</span>
                  </div>
                    <div class="chart-container p-2">
                      <canvas ref="chart1"></canvas>
                      <div class="up text-center">
                          <span v-if="this.computedDashboard.cost.growth > 0">📈</span>
                          <span v-else>📉</span>
                          <span :style="{'color': this.computedDashboard.cost.growth > 0 ? 'rgb(41, 169, 146)' : 'red' }"><b>{{ this.computedDashboard.cost.growth+'%' }}</b></span>
                      </div>
                    </div>
                  <div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card">
                <div class="d-flex flex-row">
                  <div>
                    <span style="font-size: 14px;">{{"🧑 Transactions "+"("+Object.keys(this.computedDashboard.transactions)[0]+")"}}</span>
                    <h4><b>{{ this.computedDashboard.transactions[Object.keys(this.computedDashboard.transactions)[0]] }}</b></h4>
                    <span>average (2 months)</span>
                  </div>
                    <div class="chart-container p-2">
                      <canvas ref="chart2"></canvas>
                      <div class="up text-center">
                        <span v-if="this.computedDashboard.transactions.growth > 0">📈</span>
                        <span v-else>📉</span>
                        <span :style="{'color': this.computedDashboard.transactions.growth > 0 ? 'rgb(41, 169, 146)' : 'red' }"><b>{{ this.computedDashboard.transactions.growth+'%' }}</b></span>
                      </div>
                    </div>
                  <div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card">
                <div class="d-flex flex-row">
                  <div>
                    <span style="font-size: 14px;">{{"✅ Approved "+"("+Object.keys(this.computedDashboard.approved)[0]+")"}}</span>
                    <h4><b>{{ this.computedDashboard.approved[Object.keys(this.computedDashboard.approved)[0]] }}</b></h4>
                    <span>average (2 months)</span>
                  </div>
                    <div class="chart-container p-2">
                      <canvas ref="chart3"></canvas>
                      <div class="up text-center">
                        <span v-if="this.computedDashboard.approved.growth > 0">📈</span>
                        <span v-else>📉</span>
                        <span :style="{'color': this.computedDashboard.approved.growth > 0 ? 'rgb(41, 169, 146)' : 'red' }"><b>{{ this.computedDashboard.approved.growth+'%' }}</b></span>
                      </div>
                    </div>
                  <div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="card">
                <div class="d-flex flex-row">
                  <div>
                    <span>{{"👐 Claimed "+"("+Object.keys(this.computedDashboard.claimed)[0]+")"}}</span>
                    <h4><b>{{ this.computedDashboard.claimed[Object.keys(this.computedDashboard.claimed)[0]] }}</b></h4>
                    <span>average (2 months)</span>
                  </div>
                    <div class="chart-container p-2">
                      <canvas ref="chart4"></canvas>
                      <div class="up text-center">
                        <span v-if="this.computedDashboard.claimed.growth > 0">📈</span>
                        <span v-else>📉</span>
                        <span :style="{'color': this.computedDashboard.claimed.growth > 0 ? 'rgb(41, 169, 146)' : 'red' }"><b>{{ this.computedDashboard.claimed.growth+'%' }}</b></span>
                      </div>
                     </div>
                  <div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex mt-4">
          <div class="row w-100 mx-auto">
            <div class="col-8">
              <div class="card">
                <div>
                  <h5><b>Yearly Assistance</b></h5>
                </div>
                <canvas ref="chart5" class="chart5"></canvas>            
              </div>
            </div>
            <div class="col-4">
              <div class="card">
                <canvas ref="chart6" class="chart6"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex mt-4">
          <div class="row w-100 mx-auto">
            <div class="col-6">
              <div class="card">
                <canvas ref="chart7"></canvas>
              </div>
            </div>
            <div class="col-6">
              <div class="card">
                <div>
                  <div class="row">
                    <div class="border-bottom">
                      <h5><b>SMS Status</b></h5>
                    </div>
                    <div class="col-6 mt-2" style="border-right: 1px solid rgb(204, 206, 207)">
                      <div class="d-flex flex-row">
                        <div class="mini-circle bg-success"></div>
                        <span class="px-2">Delivered</span>
                      </div>
                      <div class="mt-2 d-flex flex-row">
                        <h1><b>{{ this.computedDashboard.sent_sms.sms_delivered_count }}</b></h1>
                        <div class="mx-3">
                            <div class="up-2 text-center">
                              <span v-if="this.computedDashboard.sent_sms.growth_delivered > 0">📈</span>
                              <span v-else>📉</span>
                              <span style="color:rgb(41, 169, 146)"><b>{{this.computedDashboard.sent_sms.growth_delivered}}</b></span>
                            </div>
                            from last month
                        </div>
                      </div>
                    </div>
                    <div class="col-6 mt-2">
                      <div class="d-flex flex-row">
                        <div class="mini-circle bg-danger"></div>
                        <span class="px-2">Failed</span>
                      </div>
                      <div class="mt-2 d-flex flex-row">
                        <h1><b>{{ this.computedDashboard.sent_sms.sms_failed_count }}</b></h1>
                        <div class="mx-3">
                            <div class="up-2 text-center">
                              <span v-if="this.computedDashboard.sent_sms.growth_failed > 0">📈</span>
                              <span v-else>📉</span>
                              <span style="color:rgb(41, 169, 146)"><b>{{this.computedDashboard.sent_sms.growth_failed}}</b></span>
                            </div>
                            from last month
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <style scoped>

  .mini-circle{
    border-radius: 50%;
    height: 20px;
    width: 20px;
  }

  .chart5{
    height: 350px !important;
    width: 100% !important;
  }

  .chart6{
    height: 380px !important;
    width: 100% !important;
  }

  .up-2{
    border-radius: 10px;    
    background-color: rgb(214, 239, 235);
    width: 65%;
  }

  .up2{
    border-radius: 10px;    
    background-color: rgb(214, 239, 235);
    width: 50%;
  }

  .card {
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    box-sizing: border-box;
    padding: 10px;
  }
  
  .dashboard {
    padding-left: 250px;
    overflow-x: hidden;
    height: -webkit-calc(100% - 50px);
    height: -moz-calc(100% - 50px);
    height: calc(100% - 50px);
  }
  
  .chart-container {
    position: relative;
    width: 50%;
    height: 65px;
  }
  
  .chart-container canvas {
    width: 100% !important;
    height: 100% !important;
  }
  </style>
  
  <script>
  import auth from '../../router/auth_roles_permissions.js';
  import Loader2 from '../admin/Loader2.vue';

  import {
    Chart as ChartJS,
    BarController,
    BarElement,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    DoughnutController,
  } from 'chart.js';
  
  // Register chart components
  ChartJS.register(
    ArcElement,
    DoughnutController,
    LineController,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
    Title,
    Tooltip,
    Legend,
    BarController,
    BarElement,
  );
  export default {
    components:{
      Loader2,
    },
    watch: {
      computedDashboardLoader(newVal) {
        if (!newVal) {
          this.$nextTick(() => this.charts());
        }
      }
    },
    mounted() {
          
      
      
    },
    beforeUnmount() {
      document.body.style.overflow = 'auto';
    },
    beforeMount(){

      document.body.style.overflow = 'hidden';
      this.computedHeaderText = 'Dashboard';
      this.computedDashboardLoader = true;
      this.$store.dispatch('fetchDashboardCost').then(response => {
        this.$store.dispatch('fetchDashboardTransactions').then(response => {
          this.$store.dispatch('fetchDashboardApproved').then(response => {
            this.$store.dispatch('fetchDashboardClaimed').then(response => {
              this.$store.dispatch('fetchDashboardYearlyAssistance').then(response => {
                this.$store.dispatch('fetchDashboardBarangay').then(response => {
                  this.$store.dispatch('fetchDashboardAssistanceDescription').then(response =>{
                    this.$store.dispatch('fetchDashboardSentSMS').then(response => {
                      this.computedDashboardLoader = false;
                    })
                  });
                });
              });
            });
          });
        })
      });
    },
    methods:{
      charts(){
          const ctx_top_1 = this.$refs.chart1.getContext('2d'); 
          const ctx_top_2 = this.$refs.chart2.getContext('2d'); 
          const ctx_top_3 = this.$refs.chart3.getContext('2d');
          const ctx_top_4 = this.$refs.chart4.getContext('2d');

          const ctx_mid_1 = this.$refs.chart5.getContext('2d');
          const ctx_mid_2 = this.$refs.chart6.getContext('2d');
          const ctx_bot_1 = this.$refs.chart7.getContext('2d');
      
          const data_cost = {
          labels: [ Object.keys(this.computedDashboard.cost)[0], Object.keys(this.computedDashboard.cost)[1], Object.keys(this.computedDashboard.cost)[2]],
          datasets: [
            {
              label: 'Costing Last 3 Months',
              data: [this.computedDashboard.cost[Object.keys(this.computedDashboard.cost)[2]], this.computedDashboard.cost[Object.keys(this.computedDashboard.cost)[1]], this.computedDashboard.cost[Object.keys(this.computedDashboard.cost)[0]]], // Example data
              borderColor: this.computedDashboard.cost.growth > 0 ? 'rgb(41, 169, 146)' : 'rgba(255, 99, 132, 1)', // Line color
              backgroundColor: 'rgba(255, 99, 132, 0.2)', // Background (not visible since we have no fill)
              pointStyle: 'circle',
              pointRadius: 0, // Hide points
              pointHoverRadius: 0, // Hide hover effect for points
              fill: false, // No fill under the line
              tension: 0.4 // Smooth the line
              
            }
          ]
        };

        const config_cost = {
          type: 'line',
          data: data_cost,
          options: {
            responsive: true,
            maintainAspectRatio: true, // Allow the chart to fill the container
            plugins: {
              tooltip: {
                enabled: false // Disable tooltips
              },
              legend: {
                display: false // Disable the legend
              }
            },
            scales: {
              x: {
                display:false,
                grid: {
                  display: false // Hide x-axis grid lines
                },
                ticks: {
                  display: false // Hide x-axis ticks
                },
                title: {
                  display: false // Hide x-axis title/labels
                }
              },
              y: {
                display:false,
                beginAtZero: true, // Start y-axis from zero
                grid: {
                  display: false // Hide y-axis grid lines
                },
                ticks: {
                  display: false // Hide y-axis ticks
                },
                title: {
                  display: false // Hide y-axis title/labels
                }
              }
            }
          }
        };

        const data_transactions = {
          labels: [ Object.keys(this.computedDashboard.transactions)[0], Object.keys(this.computedDashboard.transactions)[1], Object.keys(this.computedDashboard.transactions)[2]],
          datasets: [
            {
              label: 'Transactions Last 3 Months',
              data: [this.computedDashboard.transactions[Object.keys(this.computedDashboard.transactions)[2]], this.computedDashboard.transactions[Object.keys(this.computedDashboard.transactions)[1]], this.computedDashboard.cost[Object.keys(this.computedDashboard.transactions)[0]]], // Example data
              borderColor: this.computedDashboard.transactions.growth > 0 ? 'rgb(41, 169, 146)' : 'rgba(255, 99, 132, 1)', // Line color
              backgroundColor: 'rgba(255, 99, 132, 0.2)', // Background (not visible since we have no fill)
              pointStyle: 'circle',
              pointRadius: 0, // Hide points
              pointHoverRadius: 0, // Hide hover effect for points
              fill: false, // No fill under the line
              tension: 0.4 // Smooth the line
              
            }
          ]
        };

        const config_transactions = {
          type: 'line',
          data: data_transactions,
          options: {
            responsive: true,
            maintainAspectRatio: true, // Allow the chart to fill the container
            plugins: {
              tooltip: {
                enabled: false // Disable tooltips
              },
              legend: {
                display: false // Disable the legend
              }
            },
            scales: {
              x: {
                display:false,
                grid: {
                  display: false // Hide x-axis grid lines
                },
                ticks: {
                  display: false // Hide x-axis ticks
                },
                title: {
                  display: false // Hide x-axis title/labels
                }
              },
              y: {
                display:false,
                beginAtZero: true, // Start y-axis from zero
                grid: {
                  display: false // Hide y-axis grid lines
                },
                ticks: {
                  display: false // Hide y-axis ticks
                },
                title: {
                  display: false // Hide y-axis title/labels
                }
              }
            }
          }
        };

        const data_approved = {
          labels: [ Object.keys(this.computedDashboard.approved)[0], Object.keys(this.computedDashboard.approved)[1], Object.keys(this.computedDashboard.approved)[2]],
          datasets: [
            {
              label: 'Approved Assistance Last 3 Months',
              data: [this.computedDashboard.approved[Object.keys(this.computedDashboard.approved)[2]], this.computedDashboard.approved[Object.keys(this.computedDashboard.approved)[1]], this.computedDashboard.approved[Object.keys(this.computedDashboard.approved)[0]]], // Example data
              borderColor: this.computedDashboard.approved.growth > 0 ? 'rgb(41, 169, 146)' : 'rgba(255, 99, 132, 1)', // Line color
              backgroundColor: 'rgba(255, 99, 132, 0.2)', // Background (not visible since we have no fill)
              pointStyle: 'circle',
              pointRadius: 0, // Hide points
              pointHoverRadius: 0, // Hide hover effect for points
              fill: false, // No fill under the line
              tension: 0.4 // Smooth the line
              
            }
          ]
        };

        const config_approved = {
          type: 'line',
          data: data_approved,
          options: {
            responsive: true,
            maintainAspectRatio: true, // Allow the chart to fill the container
            plugins: {
              tooltip: {
                enabled: false // Disable tooltips
              },
              legend: {
                display: false // Disable the legend
              }
            },
            scales: {
              x: {
                display:false,
                grid: {
                  display: false // Hide x-axis grid lines
                },
                ticks: {
                  display: false // Hide x-axis ticks
                },
                title: {
                  display: false // Hide x-axis title/labels
                }
              },
              y: {
                display:false,
                beginAtZero: true, // Start y-axis from zero
                grid: {
                  display: false // Hide y-axis grid lines
                },
                ticks: {
                  display: false // Hide y-axis ticks
                },
                title: {
                  display: false // Hide y-axis title/labels
                }
              }
            }
          }
        };

        const data_claimed = {
          labels: [ Object.keys(this.computedDashboard.claimed)[0], Object.keys(this.computedDashboard.claimed)[1], Object.keys(this.computedDashboard.claimed)[2]],
          datasets: [
            {
              label: 'Claimed Assistance Last 3 Months',
              data: [this.computedDashboard.claimed[Object.keys(this.computedDashboard.claimed)[2]], this.computedDashboard.claimed[Object.keys(this.computedDashboard.claimed)[1]], this.computedDashboard.claimed[Object.keys(this.computedDashboard.claimed)[0]]], // Example data
              borderColor: this.computedDashboard.claimed.growth > 0 ? 'rgb(41, 169, 146)' : 'rgba(255, 99, 132, 1)', // Line color
              backgroundColor: 'rgba(255, 99, 132, 0.2)', // Background (not visible since we have no fill)
              pointStyle: 'circle',
              pointRadius: 0, // Hide points
              pointHoverRadius: 0, // Hide hover effect for points
              fill: false, // No fill under the line
              tension: 0.4 // Smooth the line
              
            }
          ]
        };

        const config_claimed = {
          type: 'line',
          data: data_claimed,
          options: {
            responsive: true,
            maintainAspectRatio: true, // Allow the chart to fill the container
            plugins: {
              tooltip: {
                enabled: false // Disable tooltips
              },
              legend: {
                display: false // Disable the legend
              }
            },
            scales: {
              x: {
                display:false,
                grid: {
                  display: false // Hide x-axis grid lines
                },
                ticks: {
                  display: false // Hide x-axis ticks
                },
                title: {
                  display: false // Hide x-axis title/labels
                }
              },
              y: {
                display:false,
                beginAtZero: true, // Start y-axis from zero
                grid: {
                  display: false // Hide y-axis grid lines
                },
                ticks: {
                  display: false // Hide y-axis ticks
                },
                title: {
                  display: false // Hide y-axis title/labels
                }
              }
            }
          }
        };

        const data_yearly_assistance = {
          labels: this.computedDashboard.yearly_assistance[0].map(value => {
                  return value.month;
                }),
          datasets: [{
            label: 'Approved Assistance',
            data:  this.computedDashboard.yearly_assistance[0].map(value => {
                  return value.cost;
                }),
            fill: false,
            borderColor: 'rgb(41, 169, 146)',
            tension: 0.1
          }]
        };


        const config_yearly_assistance = {
          type: 'line',
          data: data_yearly_assistance,
          options: {
            interaction: {
              mode: 'nearest',
              axis: 'x',
              intersect: false // Try both true/false based on behavior
            }
          }
        };

          const data_barangay = {
              labels: this.computedDashboard.barangay.transaction.map(value => {
                return value.barangay;
              }),
              datasets: [
                  {
                      data: this.computedDashboard.barangay.transaction.map(value => {
                        return value.client_sum;
                      }),
                      backgroundColor: this.computedDashboard.barangay.transaction.map(value =>{
                        return value.color
                      }),
                      // hoverBackgroundColor: [documentStyle.getPropertyValue('--p-cyan-400'), documentStyle.getPropertyValue('--p-orange-400'), documentStyle.getPropertyValue('--p-gray-400')]
                  }
              ]
          };

          const config_barangay = {
                    type:'doughnut',
                    data: data_barangay,
                    options: {
                      responsive: true,
                      maintainAspectRatio: true,
                      plugins: {
                        legend: {
                          labels: false,
                          position: 'top',
                        },
                        title: {
                          display: true,
                          text: 'Barangay '+"("+this.computedDashboard.barangay.today_date+")"
                        }
                      }
                    },
                };

          const data_assistance_description = {
          labels: this.computedDashboard.assistance_description.transaction_label, // You need to define labels too
          datasets: [
            {
              label: 'Assistance Description',
              data: this.computedDashboard.assistance_description.transaction_data, // data must be an array
              borderColor: this.computedDashboard.assistance_description.transaction_colors,
              backgroundColor: this.computedDashboard.assistance_description.transaction_colors,
              borderWidth: 2,
              borderRadius: 5, // for fully rounded bars
              borderSkipped: false,
            },
          ]
        };

        const config_assistance_description = {
          type: 'bar',
          data: data_assistance_description,
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: {
                labels:false,
                position: 'top',

              },
              title: {
                display: true,
                text: 'Assistance Description'
              }
            }
          }
        };

          new ChartJS(ctx_top_1, config_cost); 
          new ChartJS(ctx_top_2, config_transactions);
          new ChartJS(ctx_top_3, config_approved); 
          new ChartJS(ctx_top_4, config_claimed); 

          new ChartJS(ctx_mid_1, config_yearly_assistance);
          new ChartJS(ctx_mid_2, config_barangay);
          new ChartJS(ctx_bot_1, config_assistance_description );
      }
    },
    computed:{
      computedHeaderText: {
            get() {
                return this.$store.state.headerText;
            },
            set(value) {
                this.$store.commit('setHeaderText', { headerText: value });
            }
        },
        computedDashboardLoader:{
          get(){
            return this.$store.state.dashboardLoader;
          },
          set(value){
            this.$store.commit('setDashboardLoader',{'dashboardLoader':value});
          }
        },
        computedDashboard:{
          get(){
            return this.$store.state.dashboard;
          }
        }
    },
    data() {
      return {};
    },
  
    beforeRouteEnter(to, from, next) {
      auth(to).then(response => {
        if (response) {
          next(response);
        } else {
          next('/admin');
        }
      });
    }
  };
  </script>
  